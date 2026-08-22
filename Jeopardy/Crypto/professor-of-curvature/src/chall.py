from Crypto.Util.number import getPrime
from libnum import s2n
import secrets

flag = open("flag.txt", "rb").read()
flag = s2n(flag)
p = getPrime(512)
q = getPrime(512)
N = p * q
ori = secrets.randbelow(N)
secret = ori

e = 5
k = 5
base = secrets.randbelow(N)
delta = secrets.randbelow(6767)

l = [secrets.randbelow(p) for _ in range(k-1)]
cs = [(base + l[_] * delta) % N for _ in range(k-1)]
cs.append(-sum(cs) % N)
l.append((cs[-1] - base) * pow(delta, -1, N) % N)


cts = []
for c in cs:
    secret ^= c
    cts.append(pow(c, e, N))

assert len(l) == len(cts) == k
print(f'{N=}\n')
print(f'{l=}\n')
print(f'{cts=}\n')
print(f'{secret=}\n')

guess = int(input("Your guess: "))
if guess == ori:
    print("Correct!")
    print(f"FLAG: {flag}")
else:
    print("Bzzt!")