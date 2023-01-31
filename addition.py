import requests
from bs4 import BeautifulSoup
import requests
import socket

def write():
    global URL
    print("What Is the URL for the hosts file?")
    URL = input("URL: ")
    headers = {
        'User-Agent': 'Ping-Blocker v.1.00',
        'From': 'sec@badping.live'  
    }
    r = requests.get(URL, headers=headers)
    soup = BeautifulSoup(r.content, 'html5lib')
    hosts_find = soup.find('html') 
    for row in hosts_find.findAll('body'):
        url_text= row.text
    hosts = '\n'+'# Start of entries inserted by Pings software for Windows 10. Please do not put any host names in here. Url used: '+URL+'\n'+url_text+'\n'+'# End of entries inserted by Pings software for Windows 10'
    filepath = "C:\\Windows\\System32\\drivers\\etc\\hosts"
    with open(filepath, "a") as f:
        f.write(hosts)
    
def sendkey():
    # key = secrets.token_urlsafe(40)   depricated, uses import secrets
    hostname = socket.gethostname() # using this instead of "os.getenv['COMPUTERNAME']" because i heard getenv doesnt work on linux and only windows.
    url = URL
    payload = {'hostname': hostname, 'url': url,} # used  'key': key, but depricated for secure key storing
    r= requests.post("https://talk.badping.live/login/test/register.php", data=payload)
    print(r.status_code)

write()
sendkey()