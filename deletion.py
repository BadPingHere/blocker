import requests
from bs4 import BeautifulSoup
import requests
import secrets
import socket
from urlextract import URLExtract
line_number = 0
hosts_links = []
deletelines = []
endline_hosts_links = []
deletelines = []
startline_hosts_links = []
extractor = URLExtract

def readhosts():
    global line_number
    with open("C:\\Users\\davon\\Desktop\\hosts") as f:
        for line in f:
            line_number +=1
            if "Start of entries inserted by Pings software for Windows 10" in line: # Finds lines with our line
                startline_hosts_links.append(line_number)
                for url in extractor.gen_urls(line):  # Find the url in those lines
                    hosts_links.append(url) # hosts_links[0]-all
            if "End of entries inserted by Pings software for Windows 10" in line: # Finds lines with our line
                endline_hosts_links.append(line_number)

def deletefile(deletenumber):
    try:
        with open("C:\\Users\\davon\\Desktop\\hosts", 'r') as fr:
            lines = fr.readlines()
            ptr = 1
            with open("C:\\Users\\davon\\Desktop\\hosts", 'w') as fw:
                for line in lines:
                    if ptr not in deletenumber:
                        fw.write(line)
                    ptr += 1
    except:
        print("Oops! something error")

def findlines(alllinenumbers, option_number):
    try:
        if alllinenumbers -1 == endline_hosts_links[option_number]:
            deletefile(deletelines) # delete lines
            return
            option_number +=1 # this stuff if we need to delete all, in the case of removal.py
            findlines()
        else:
            # print(alllinenumbers) prints all of our numbers i guess?!
            deletelines.append(alllinenumbers)
            alllinenumbers +=1
            findlines(alllinenumbers, option_number)
    except IndexError:
        return()
        print('Reached the end of the list!') # wont print, but its there ig
                          
def processhosts():
    def let_user_pick(options):
        print("What Host would you like removed?")
        for idx, element in enumerate(options):
            print("{}) {}".format(idx + 1, element))
        global i
        i = input("Enter number: ")
        try:
            if 0 < int(i) <= len(options):
                return int(i) - 1
        except:
            pass
        return None
    options = hosts_links
    res = let_user_pick(options)
    print("Please enter the key for the URL chosen.")
    key_param = input("Key: ")
    url_param = options[res]
    checkinfo(key_param, url_param)
        
def checkinfo(key, url):
    hostname = socket.gethostname() # using this instead of "os.getenv['COMPUTERNAME']" because i heard getenv doesnt work on linux and only windows.
    payload = {'hostname': hostname, 'url': url, 'key': key}
    r= requests.post("https://talk.badping.live/login/test/login.php", data=payload)
    if r.status_code == 202:
        option_number = int(i) - 1
        alllinenumbers = startline_hosts_links[option_number]
        findlines(alllinenumbers, option_number)

readhosts()
processhosts()
        