##zRAT是什么
zRAT是一个统一的主机在线管理应用，管理端使用PHP编写，只要客户端遵循通信协议，都能实现管理。

##进程
目前计划先实现管理端，还有win下的客户端

由于技术原因，暂时放弃通信加密

##目录结构
* /client 客户端源码
* /client/win32 win32客户端源码（C/C++）
* /mgmt 管理后台(PHP)
* /server 上线服务端（PHP）

##设计
* 客户端遵循协议，可以有多种系统版本客户端(win, android, linux)
* （`/auth`、`/hook`、`/resp`）三项与管理后台可以分离，中介为数据库
* 管理后台前端全静态，通过ajax操作

###上线步骤
* *注：除了sid还有key，其它内容全部用第一步的key加密*
* **注册：**客户端请求`/auth?sid=xxx&key=xxx&data=xxx`(POST)。(sid为客户端生成的100～200位的随机字符串来当客户端的ID[如果sid已经存在则使用旧的sid]，key(200位)用来AES加密后面的通信内容[key每次上线都更新]。如果返回status=0则重新生成sid和key，重复此步骤，直至status=1)
* **获取命令：**客户端每3分钟请求一次`/hook?sid=xxx`(POST)。(如果404则返回第一步重新注册，如果不为空则AES解密[解密不了则返回第一步重新注册]，解析内容并执行对应命令。如果有命令执行则到下一步)(如果获取到命令则切换到每10秒请求一次，连续10次没有命令的话则切换回3分钟请求一次)
* **发送命令执行结果：**客户端请求`resp?sid=xxx&cid=xxx`(POST)。(内容AES加密，并根据协议发送命令执行结果。如果返回404则重新注册)

###上线的管理端处理
* `/auth`
 * 判断sid还有key的长度和value(大小写字母和数字)是否符合规范，否返回status=0，真则继续下一步
 * 判断sid是否已经存在，真则判断是否在线，不在线返回status=1，在线则继续下一步。（防止两客户端同SID）
 * 将sid，key和主机信息存如数据库
* `/hook`:
 * 判断sid是否存在，否则返回404
 * 如果有命令则AES加密后并返回加密数据
* `/resp`：
 * 判断sid和cid是否存在，如果存在则解密写入数据库，返回空白。如果解密不了或者sid不存在则返回404。


###客户端与管理端协议
* 管理端发送
 * id:(命令的ID)
 * command:(需要执行的命令)
 * -exec(执行系统命令):data为命令内容
 * -download(客户端下载文件):data为文件URL和文件保存地址
 * -open(客户端打开文件):data为文件地址
 * -screenshot(获取屏幕截图):data为空
 * -sysinfo(获取系统信息):data为空
 * -upload(上传文件):data为空(实现是把文件用AES加密并当作图片上传到其它网站，返回文件网址还有用来AES加密的密码)
 * -ll(列出文件和目录):data为目录地址
 * data:(命令里的参数)
* 客户端发送
 * sid:(每个客户端的ID)
 * id:(命令的ID，这样才能知道是哪条命令的返回结果)
 * status:(命令执行结果) 0(成功)|1(失败)
 * data:(命令执行返回的结果)
 * timestamp:(客户端的时间)

###数据库设计
* users 存放系统用户信息
 * id
 * username
 * password
 * mgmt_time
* hosts 主机信息
 * id
 * sid 100位字符串ID
 * key AES加密密码
 * pub_ip 公网IP
 * ip IP地址
 * username 客户端当前用户名
 * hostname 主机名
 * os 主机系统
 * label 备注
 * time 添加时间
 * last_time 最后一次请求时间
 * mgmt_time 
* commands 命令信息
 * id
 * sid 字符串ID，用来发送给客户端，防止遍历
 * hid 对应的主机id
 * status 命令执行状态 -1(超时)|0(等待执行)|1(已经执行)
 * command 要执行的命令
 * data 命令需要的参数
 * time 添加时间
 * timestamp:防止重复提交
* resps 命令执行结果
 * id
 * cid 命令的id
 * data 返回数据
 * time 添加时间
* logs 登录日志
 * id 
 * data
 * time
 
###后台设计
 * /api?action=xxx
  * login(POST)登录: username, password 登录成功status=1，并获取token。{"status":0,"data":{"token":""}}
  * logout(POST)注销: username, token
  * chgPassword(POST): username, token, password, newPassword
  * gethosts(POST)获取主机列表: username, token, offset, rows
  * gethost(POST)获取主机信息： username, token, id
  * updateHost(POST)更新主机标签: username, token, id, label
  * getCommands(POST)获取命令列表: username, token, offset, rows[, hid][, status]
  * addCommand(POST)添加命令: username, token, hid, command, data, timestamp
  * getResp(POST)获取命令执行结果: username, token, cid
 
 
##License
* Author：[Ambulong](https://github.com/Ambulong)
* E-Mail：[zeng.ambulong@gmail.com](mailto:zeng.ambulong@gmail.com)
* This software is licenced under the [GPL 2.0](http://www.gnu.org/licenses/gpl-2.0.html).