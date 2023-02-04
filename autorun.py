import getpass
import os
USER_NAME = getpass.getuser()
startline_hosts_content = []
endline_hosts_content = []


def add_to_startup(file_path=""):
    if file_path == "":
        file_path = os.path.dirname(os.path.realpath(__file__))
    bat_path = r'C:\Users\%s\AppData\Roaming\Microsoft\Windows\Start Menu\Programs\Startup' % USER_NAME
    with open(bat_path + '\\' + "pingstartup.bat", "w+") as bat_file:
        bat_file.write(r'start "" "%s"' % file_path)

def reahosts():
    # read file
    with open("C:\\Windows\\System32\\drivers\\etc\\hosts") as f:
        for line in f:
            if "Start of entries inserted by Pings software for Windows 10" in line: # Finds lines with our line
                startline_hosts_content.append(line)
            if "End of entries inserted by Pings software for Windows 10" in line: # Finds lines with our line
                endline_hosts_content.append(line)
                
# add_to_startup(file_path="")