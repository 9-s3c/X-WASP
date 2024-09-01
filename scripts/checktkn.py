#made by monarch60
from requests_oauthlib import OAuth1Session
import os
import sys


def checkauth(oauth_token, oauth_token_secret):
    verify_credentials_url = "https://api.twitter.com/1.1/account/verify_credentials.json"
    twitter = OAuth1Session(
        client_key=open("/var/www/html/auth.config","r").read().split("\n")[0].split("\t")[-1],
        client_secret=open("/var/www/html/auth.config","r").read().split("\n")[1].split("\t")[-1],
        resource_owner_key=oauth_token,
        resource_owner_secret=oauth_token_secret
    )
    response = twitter.get(verify_credentials_url)
    if response.status_code == 200:
        return(True)
    else:
        return(False)

def removechar(instr,character):
    out = ""
    for i in list(instr):
        if i == character:
            pass
        else:
            out += i
    return(out)

def main():
    out = ""
    lines = removechar(removechar(open("/var/www/html/data/tokens.temp",'r').read(),">"),"^").split("\n")
    for ln in lines:
        try:
            if ln == lines[-1]:
                if checkauth(ln.split("|")[0],ln.split("|")[1]):
                    out += ln + "^"
                else:
                    out += ln + ">"
            else:
                if checkauth(ln.split("|")[0],ln.split("|")[1]):
                    out += ln + "^\n"
                else:
                    out += ln + ">\n"
        except:
            pass
    return(out)

op = main()
print(op)
open("/var/www/html/data/tokens.temp",'w').write(op)
