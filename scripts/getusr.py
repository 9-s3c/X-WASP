#made by monarch60
from requests_oauthlib import OAuth1Session
import os
    
def get_twitter_username(oauth_token, oauth_token_secret):
    verify_credentials_url = "https://api.twitter.com/1.1/account/verify_credentials.json"
    twitter = OAuth1Session(
        client_key=open("/var/www/html/auth.config","r").read().split("\n")[0].split("\t")[-1],
        client_secret=open("/var/www/html/auth.config","r").read().split("\n")[1].split("\t")[-1],
        resource_owner_key=oauth_token,
        resource_owner_secret=oauth_token_secret
    )
    response = twitter.get(verify_credentials_url)
    if response.status_code == 200:
        user_info = response.json()
        username = user_info.get("screen_name")
        if username:
            return username
        else:
            print("Username not found in response.")
            return None
    else:
        print(f"Failed to retrieve user information. Status code: {response.status_code}")
        print(f"Response content: {response.text}")
        return None

open("/var/www/html/data/tokens.temp",'a').close()
os.system("chmod 777 /var/www/html/data/tokens.temp")
out = ""
lines = open("/var/www/html/data/tokens.temp",'r').read().split("\n")
for ln in lines:
    if "@" in ln:
        out += ln + "\n"
    else:
        if len(ln.split("|")) == 3:
            usrn = get_twitter_username(ln.split("|")[0],ln.split("|")[1])
            predat = ""
            etry = open("/var/www/html/data/tokens.temp",'r').read().split("\n")[-2].split("|")
            for l in etry:
                if l == etry[-1]:
                    predat += "@"+usrn
                else:
                    predat += l + "|"
            if ln == lines[-1]:
                out += predat
            else:
                out += predat+"\n"
            usrn = ""
        else:
            pass

open("/var/www/html/data/tokens.temp",'w').write(out)
