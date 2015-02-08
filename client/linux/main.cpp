#include <iostream>
#include <ctime>

using namespace std;

string url = "localhost";   //上线地址
int times = 3000;           //请求周期 ms
string sid = "";
string key = "";

/**
* @brief get randon string
*/
string getSalt(int len = 100);

bool chkEnv();
bool reg();
string getCommand();
bool sendResult(string cid, int status, string data);


int main()
{
    srand(time(NULL));
    sid = getSalt(123);
    cout << "sid:" << sid << endl;
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


