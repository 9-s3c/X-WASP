#made by monarch60
import tweepy
import os
import sys

consumer_key = open("/var/www/html/auth.config","r").read().split("\n")[0].split("\t")[-1]
consumer_secret = open("/var/www/html/auth.config","r").read().split("\n")[1].split("\t")[-1]
access_token = open("/var/www/html/data/urlin.temp","r").read().split("|")[1]
access_secret = open("/var/www/html/data/urlin.temp","r").read().split("|")[2]
client = tweepy.Client(
    consumer_key = consumer_key, consumer_secret=consumer_secret,
    access_token=access_token, access_token_secret=access_secret)
client.delete_tweet(id=open("/var/www/html/data/urlin.temp","r").read().split("|")[0].split("/")[-1], user_auth=True)
