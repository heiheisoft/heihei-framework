<h1 align="center">黑黑后台管理系统</h1>
黑黑后台管理系统是基于[Yii2](http://www.yiiframework.com/), [Vue](https://github.com/vuejs/vue),Bootstrap开发。<a target="_blank" href="//shang.qq.com/wpa/qunwpa?idkey=e7736f58b122088b535e2dcdf722dc16145fe288ea732054487af661d8cc9f35">加入PHP学习交流群</a>一起交流学习、框架改进、框架数据库。

```
包含功能
    权限管理
    定时任务管理
    简单队列应用
```

安装
-------------------
```
下载扩展
    php composer update

初始化
    php init
```

目录结构
-------------------
```
common
    config/              包含共享配置
    models/              包含项目使用的模型类
console
    config/              包含控制台配置
    controllers/         控制台控制器
    jobs/                定时任务，消息队列具体的工作类
    runtime/             运行时生成的文件
apps
    manage/
        config/              后台管理配置文件
        controllers/         后台管理控制器
        runtime/             后台管理运行时生成的文件
        views/               后台管理视图文件
    rest/
        config/              接口配置文件
        controllers/         接口控制器
        runtime/             运行时生成的文件
modules
    manage/              后台管理接口模块
    rest/                接口模块
webs
    manage/              脚本和Web资源
    rest/                脚本和Web资源
vendor/                  相关的第三方包
environments/            环境相关覆盖
```
