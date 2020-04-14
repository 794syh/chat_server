<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    //命名空间
//    'app_namespace' => "App",
    // 应用调试模式
    'app_debug' => true,
    // 应用Trace
    'app_trace' => false,
    // 应用模式状态
    'app_status' => '',
    // 是否支持多模块
    'app_multi_module' => true,
    // 入口自动绑定模块
    'auto_bind_module' => false,
    // 注册的根命名空间
    'root_namespace' => [],
    // 扩展函数文件
    'extra_file_list' => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type' => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return' => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler' => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler' => 'callback',
    // 默认时区
    'default_timezone' => 'PRC',
    // 是否开启多语言
    'lang_switch_on' => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter' => '',
    // 默认语言
    'default_lang' => 'zh-cn',
    // 应用类库后缀
    'class_suffix' => false,
    // 控制器类后缀
    'controller_suffix' => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module' => 'Index',
    // 禁止访问模块
    'deny_module_list' => ['common'],
    // 默认控制器名
    'default_controller' => 'Index',
    // 默认操作名
    'default_action' => 'index',
    // 默认验证器
    'default_validate' => '',
    // 默认的空控制器名
    'empty_controller' => 'Error',
    // 操作方法后缀
    'action_suffix' => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo' => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch' => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr' => '/',
    // URL伪静态后缀
    'url_html_suffix' => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param' => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type' => 0,
    // 是否开启路由
    'url_route_on' => true,
    // 路由使用完整匹配
    'route_complete_match' => false,
    // 路由配置文件（支持配置多个）
    'route_config_file' => ['route'],
    // 是否开启路由解析缓存
    'route_check_cache' => false,
    // 是否强制使用路由
    'url_route_must' => true,
    // 域名部署
    'url_domain_deploy' => false,
    // 域名根，如thinkphp.cn
    'url_domain_root' => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert' => true,
    // 默认的访问控制器层
    'url_controller_layer' => 'controller',
    // 表单请求类型伪装变量
    'var_method' => '_method',
    // 表单ajax伪装变量
    'var_ajax' => '_ajax',
    // 表单pjax伪装变量
    'var_pjax' => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache' => false,
    // 请求缓存有效期
    'request_cache_expire' => null,
    // 全局请求缓存排除规则
    'request_cache_except' => [],

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template' => [
        // 模板引擎类型 支持 php think 支持扩展
        'type' => 'Think',
        // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写
        'auto_rule' => 1,
        // 模板路径
        'view_path' => '',
        // 模板后缀
        'view_suffix' => 'html',
        // 模板文件名分隔符
        'view_depr' => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin' => '{',
        // 模板引擎普通标签结束标记
        'tpl_end' => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end' => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str' => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl' => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl' => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl' => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message' => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg' => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle' => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log' => [
        // 日志记录方式，内置 file socket 支持扩展
        'type' => 'File',
        // 日志保存目录
        'path' => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace' => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache' => [
        // 驱动方式
        'type' => 'File',
        // 缓存保存目录
        'path' => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session' => [
        'id' => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix' => 'think',
        // 驱动方式 支持redis memcache memcached
        'type' => '',
        // 是否自动开启 SESSION
        'auto_start' => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie' => [
        // cookie 名称前缀
        'prefix' => '',
        // cookie 保存时间
        'expire' => 0,
        // cookie 保存路径
        'path' => '/',
        // cookie 有效域名
        'domain' => '',
        //  cookie 启用安全传输
        'secure' => false,
        // httponly设置
        'httponly' => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate' => [
        'type' => 'bootstrap',
        'var_page' => 'page',
        'list_rows' => 15,
    ],

    // +----------------------------------------------------------------------
    // | captcha验证码设置
    // +----------------------------------------------------------------------
    'captcha' => [
        //验证码字符集合
        'codeSet' => '234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        //验证码过期时间
        'expire' => 1800,
        //使用中文验证码
        'useZh' => false,
        //中文验证码字符串
        'zhSet' => '',
        //使用背景图片
        'useImgBg' => true,
        //验证码字体大小
        'fontSize' => 16,
        //是否画混淆曲线
        'useCurve' => true,
        //是否添加杂点
        'usrNoise' => true,
        //验证码图片高度
        'imageH' => 46,
        //验证码图片宽度
        'imageW' => 120,
        //验证码位数
        'length' => 4,
        //验证码字体
        'fontttf' => '5.ttf',
        //背景颜色
        'bg' => [243, 251, 254],
        //验证成功是否重置
        'reset' => true,
    ],

    // +----------------------------------------------------------------------
    // | Opensll 加密公钥
    // +----------------------------------------------------------------------
    "public_key" => "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA66qpNlx6er1ACT2Cowse
WupwDBHZ9z26Xe2hgtLoHeWrBFo5RbWPRIeMpnJI7OXzWh/mYo3geFDKJVNSAccS
36LgHcBf4J0vTRLTIpuVTZ2oopv9OZRwZ2vyGvFqZWqsaqPqiqjaPrSpcynHcdYn
BLme1PmiDo8q2AR0zN72nP9kbfI7FfQtGLfLyQmaIwPnynsx3xLM5eY1cNxOwD3H
t+FAAS/MlcuiNUREi1coCgadiJMUhB5mCdwQa6+dZbwtSAM3Q2gsPQm3DZuXx0f2
K1Ef+F8p5pnwEGd1FkcV5XHu7X+/7faDXOQHO53dMMqZtCOrrphI4vDW3kN9DVkH
+QIDAQAB
-----END PUBLIC KEY-----",

    // +----------------------------------------------------------------------
    // | Opensll 加密私钥
    // +----------------------------------------------------------------------
    "private_key" => "-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDrqqk2XHp6vUAJ
PYKjCx5a6nAMEdn3Pbpd7aGC0ugd5asEWjlFtY9Eh4ymckjs5fNaH+ZijeB4UMol
U1IBxxLfouAdwF/gnS9NEtMim5VNnaiim/05lHBna/Ia8Wplaqxqo+qKqNo+tKlz
Kcdx1icEuZ7U+aIOjyrYBHTM3vac/2Rt8jsV9C0Yt8vJCZojA+fKezHfEszl5jVw
3E7APce34UABL8yVy6I1RESLVygKBp2IkxSEHmYJ3BBrr51lvC1IAzdDaCw9CbcN
m5fHR/YrUR/4XynmmfAQZ3UWRxXlce7tf7/t9oNc5Ac7nd0wypm0I6uumEji8Nbe
Q30NWQf5AgMBAAECggEBAKiyLJimUGKGEE3NAzO1JNCN2xE4wh9Sx5KvJRth4Bhu
rCHcd/znf5vdsBgB3SP/Y1jTVvabeuCFbH6VizGMkH0XuXhyme9OdWqlnA+88X5y
6iEkqnrKQEA4LEGPKMtaNfvMRHtnbXrPljDC6msZkiq2uPTZkWD8qP2jCv0QadZt
N4aC6QI6tB9/znc1EEQMMHW7TdBVbg0TTgKGaeL2S8htm97+WQNG1tVLvM8/GM5i
6zmRVZbVNuIscWrOLiOUFMdPUHvSC0eIGErxP0VetdQf9qZt0y1aiThg6jVZ9NZh
dnjowY34yMwIWaKAt6m78YmZQL+9zndUYH/zfp+pxoECgYEA+QtzbmsVcjDxU/x1
LhLnfLhxb7lJNWjC5xercsAjYX6XG5oXyrQnmboMMcput6Bi6q9mmpu3G0JNyZhx
QWKjp3Sdc9lHBdKHDsdaMhq8gYxkMVQkiJUl0wTbhpn5TQ1Pv/vQVI7ERR8Xazej
2bMlh160enyK3ELsqpVINO9Ey2kCgYEA8j+QMXpHH9MIsBC01stFBTTuTqHcIdn5
cK8oHXs0XVuZvCwlpOXQ68sqREZsNPIx1NecKXNC1gbddASXtg7Q54H9pu8pHdnc
rlCl3l3W//ryTvhfOfEGrW4Qnlw89x9bAGOfz2SeW0+1QXQWPy0PL2kfv6Mjbk99
UqEF/CrYlhECgYEAz1D0+ooyBCkx/BRBB+W8xlpUNAkuJEPdW7qcgp3n1hUnfVa9
T4TBInt6A0jc+geN0IfLBhFyUELVtmgsf525VoEBQZsyQGqM+4SAVQ1ktwNDtLyy
3PAr2IpGpLowii/2n37kYj+mq1PCjwJ2Z5d2OkZgP0bF4a3kjexPe6Fix2ECgYEA
w0C2YRrUrvLywq6Eg8gM1piGJ7zQaDx/j/Kt261NVJ4bOI9AvDs7/IGhjKxSVQ6N
Wad7R/GOAmJAJl35RhWFDcQ5hU6pDlVmSN3aA3QRcft7SGlqn5IEts7K+EkSHoEY
YSTrT0cjWCfTyXDe6YbRAX6yx0yfpy7MrUf6Qfjq33ECgYB6MUdRZ/SSgdYwZYqJ
8ipxnF2pB0J3+ee6IGgEQ4nIGKHPLkJgD0AxpclJwfinRzJX03dIN7amLTtLMep3
7deJqfiSmYtf1VO9zCIZv9qXAminUSGOKt8lz0fUU+OSwOp+BlPZAYvsq9qj+LcZ
Q9DuNHpx4CHDMJNtgOsBdOregg==
-----END PRIVATE KEY-----"
];
