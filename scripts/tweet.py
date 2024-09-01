#made by monarch60
import tweepy
import os

sects = open("/var/www/html/data/in.temp","r").read().split("|")
consumer_key = open("/var/www/html/auth.config","r").read().split("\n")[0].split("\t")[-1]
consumer_secret=open("/var/www/html/auth.config","r").read().split("\n")[1].split("\t")[-1]
access_token = sects[0]
access_secret = sects[1]
client = tweepy.Client(
    consumer_key = consumer_key, consumer_secret=consumer_secret,
    access_token=access_token, access_token_secret=access_secret)
response = client.create_tweet(text=sects[3],reply_settings="mentionedUsers")
print("Tweet posted successfully!")
