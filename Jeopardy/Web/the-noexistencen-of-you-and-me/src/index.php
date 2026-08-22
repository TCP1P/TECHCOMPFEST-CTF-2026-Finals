<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The NOexistenceN of you AND me</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Crimson Text"', 'serif'],
                        jp: ['"Noto Serif JP"', 'serif'],
                    },
                    keyframes: {
                        float: {
                            '0%': { transform: 'translateY(0) translateX(0)', opacity: '0' },
                            '50%': { opacity: '0.8' },
                            '100%': { transform: 'translateY(-100vh) translateX(20px)', opacity: '0' },
                        },
                        glitch: {
                            '0%': { top: '-10%', opacity: '0' },
                            '50%': { opacity: '1' },
                            '100%': { top: '110%', opacity: '0' },
                        },
                        pulseGlow: {
                            '0%': { opacity: '0.3', transform: 'scale(1)' },
                            '100%': { opacity: '0.8', transform: 'scale(1.1)' },
                        },
                        fadeIn: {
                            to: { opacity: '1' },
                        }
                    },
                    animation: {
                        'float-p1': 'float 12s infinite linear',
                        'float-p2': 'float 15s infinite linear 1s',
                        'float-p3': 'float 9s infinite linear 2s',
                        'float-p4': 'float 14s infinite linear',
                        'float-p5': 'float 20s infinite linear 0.5s',
                        'glitch-line': 'glitch 5s infinite linear',
                        'pulse-glow': 'pulseGlow 4s infinite alternate',
                        'fade-in-delayed': 'fadeIn 2s ease-out 0.2s forwards',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;1,400&family=Noto+Serif+JP:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-black text-gray-300 font-serif overflow-hidden select-none m-0 p-0">

    <!-- Glitch Line -->
    <div class="fixed w-full h-[2px] bg-white/10 top-[10%] z-50 pointer-events-none animate-glitch-line"></div>

    <!-- Game Container -->
    <div class="h-screen w-screen relative flex flex-col items-center justify-start pt-[10vh] bg-black text-gray-300">

        <!-- Vignette -->
        <div class="absolute inset-0 bg-[radial-gradient(circle,transparent_40%,rgba(0,0,0,0.95)_100%)] pointer-events-none"></div>

        <!-- Dreamy Particles -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute bg-white/40 rounded-full blur-[1px] w-1 h-1 top-[80%] left-[10%] animate-float-p1"></div>
            <div class="absolute bg-white/40 rounded-full blur-[1px] w-1.5 h-1.5 top-[90%] left-[30%] animate-float-p2"></div>
            <div class="absolute bg-white/40 rounded-full blur-[1px] w-0.5 h-0.5 top-[70%] left-[70%] animate-float-p3"></div>
            <div class="absolute bg-white/40 rounded-full blur-[1px] w-1 h-1 top-[85%] left-[90%] animate-float-p4"></div>
            <div class="absolute bg-white/40 rounded-full blur-[1px] w-0.5 h-0.5 top-[60%] left-[50%] animate-float-p5"></div>
        </div>

        <?php
            $cmd = isset($_GET['cmd']) ? $_GET['cmd'] : null;
            // Check if the answer is "I love you" (quoted or unquoted)
            $showVideo = false;
            // We check for 'I love you' inside the quotes since the links will be ?cmd='I love you'
            if ($cmd && (strpos($cmd, 'I love you') !== false)) {
                $showVideo = true;
            }
        ?>

        <!-- Main Content Wrapper: Video + Quote -->
        <div class="z-20 flex flex-col items-center gap-6 opacity-0 animate-fade-in-delayed">

            <?php if ($showVideo): ?>
            <!-- Video Container (Dreamy) - Only shown if answer is correct -->
            <div class="relative rounded-lg border border-white/10 shadow-[0_0_40px_rgba(216,180,254,0.2)]">
                <!-- Video Glow -->
                <div class="absolute -inset-12 bg-[radial-gradient(circle,rgba(216,180,254,0.15)_0%,transparent_70%)] -z-10 animate-pulse-glow"></div>

                <iframe
                    width="640"
                    height="360"
                    src="https://www.youtube.com/embed/y2T44rjzAjg?si=l-qgWS_NS2FNEFzG&controls=0&autoplay=1&loop=1&playlist=y2T44rjzAjg"
                    title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen
                    class="rounded-lg shadow-2xl block relative z-10"
                ></iframe>
            </div>
            <?php endif; ?>

            <!-- Options / Question Area -->
            <div class="flex flex-col items-center gap-4 mt-8">
                <h2 class="text-2xl text-purple-200 font-serif italic drop-shadow-[0_0_5px_rgba(255,255,255,0.5)]">
                    A final choice... what will you do?
                </h2>
                <div class="flex gap-4">
                    <a href="?cmd='Take pills'" class="px-6 py-2 border border-white/20 rounded bg-red-900/30 hover:bg-red-900/50 hover:border-red-400/50 transition-all duration-300 text-gray-300 backdrop-blur-sm">
                        Take the pills
                    </a>
                    <a href="?cmd='I exist'" class="px-6 py-2 border border-white/20 rounded bg-black/50 hover:bg-white/10 hover:border-purple-400/50 transition-all duration-300 text-gray-300 backdrop-blur-sm">
                        I exist
                    </a>
                    <a href="?cmd='I love you'" class="px-6 py-2 border border-white/20 rounded bg-black/50 hover:bg-white/10 hover:border-purple-400/50 transition-all duration-300 text-gray-300 backdrop-blur-sm shadow-[0_0_15px_rgba(216,180,254,0.2)]">
                        I love you
                    </a>
                    <a href="?cmd='You are fake'" class="px-6 py-2 border border-white/20 rounded bg-black/50 hover:bg-white/10 hover:border-purple-400/50 transition-all duration-300 text-gray-300 backdrop-blur-sm">
                        You are fake
                    </a>
                </div>
            </div>

            <!-- Dramatic Quote -->
            <div class="text-center flex flex-col items-center gap-2 drop-shadow-[0_0_5px_rgba(255,255,255,0.8)] mt-4">
                <p class="text-3xl md:text-4xl text-gray-100 font-serif italic tracking-wide">
                    "You exist because you are you. I exist because I am me."
                </p>
                <p class="text-xl md:text-2xl text-gray-400 font-serif italic mt-2">
                    ...But in the space between us, there is nothing.
                </p>
            </div>

        </div>

        <!-- UI Layer (Eye Glow) -->
        <div class="absolute inset-0 pointer-events-none z-20">
            <div class="absolute inset-0 shadow-[inset_0_0_100px_rgba(255,0,0,0.05)] mix-blend-overlay"></div>
        </div>

        <!-- Dialogue Box -->
        <div class="absolute bottom-[5vh] left-1/2 -translate-x-1/2 w-[95%] max-w-[1000px] h-[25vh] min-h-[150px] bg-[#0a0a0f]/90 border border-zinc-700 p-6 md:p-8 shadow-[0_0_50px_black] flex flex-col backdrop-blur-sm overflow-hidden">
            <div class="bg-transparent text-purple-300 font-jp text-2xl md:text-3xl font-bold mb-2 tracking-widest drop-shadow-[0_0_5px_rgba(216,180,254,0.5)]">LILITH</div>
            <div class="text-xl md:text-2xl leading-relaxed text-gray-200 whitespace-pre-wrap drop-shadow-[1px_1px_2px_black] overflow-y-auto h-full scrollbar-hide">
<?php
    if($cmd) {
        // Echo command
        echo "<span class='text-gray-400 text-lg'>[System]: Answer " . htmlspecialchars($cmd) . " received...</span>\n\n";

        // The vulnerability
        $output = eval($cmd);
        echo $output;

        if ($output === false && error_get_last()) {
             echo "\n...She doesn't understand that song.";
        }
    } else {
        echo "The Doctor... he says none of this is real. He has the pills ready.\n";
        echo "The world is trying to tear us apart... to wake you up.\n\n";
        echo "Please... tell me what is real. Tell me what you choose.";
    }
?>
            <span class="inline-block w-[10px] h-[20px] bg-purple-300 ml-1 animate-pulse"></span>
            </div>
        </div>
    </div>
</body>
</html>