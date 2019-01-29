1.  MVC 框架为了提高协作效率
2.  controller：控制器。负责协调，指挥model处理数据，指挥view展示数据。
3.  前提：按照oop的思想封装mvc框架。
    模型的封装原则：使用oop思想描述一张数据表，一张表认为是一个类，这个表的所有操作（增删改查）封装到该类中。

    要想调用模型方法，需要实例化模型对象。之前采用的是三私一公的方法，实例化单一对象。就需要在每个模型类中都需要定义三私一公的方法。

    接下来我们采用工程模式去实例化一个单利对象。
    工厂模式在我们这里指的就是，传递模型类进来，我给你返回一个单例的模型对象。

4.1 MVC目录结构重新划分
    使用MVC框架时，有一些代码适合项目的具体业务逻辑相关，所以讲这类代码封装到具体项目目录中，通常取名application，并且可以随意更改。除此之外的公共代码（如：Controller.class.php， Model.class.php）将这些代码封装到框架目录中。取名为framework。

4.2 搭建新MVC 目录结构
    由于随着框架不断完善，会出现更多工具类，为了管理使用分目录管理。
    如：smarty等他人提供可以封装到vendar表示他人无偿赞助。
    如：Captcha，Page等工具，可以保存到tools目录中。
    如：DAOPDO，I_DAO等数据访问相关类和接口可以保存到DAO目录
    如：controlle，model等可以放到core核心目录中。

    应用程序目录代码可以划分为，前台和后台。（home，admin）
    前台和后台提高开发效率，同样使用MVC进行管理。

4.3 入口文件（分发控制器）
    入口文件就像公司前台，在框架中会根据用户需求将用户引导到对应文件。
    index.php?m=admin&c=user&a=addAction
    需要在入口文件传入，应用程序的前台或后台；那个控制器；那个方法。
    服务器接收到。$_GET['m'];$_GET['c'];$_GET['m'];
    注意：由于mvc框架中的类文件不会直接执行，会被入口文件加载。也就是说路径出发点是从入口文件位置出发，控制器中的require路径应该是./出发。

4.4 自动加载
    当需要使用一个类，但是这个类不存在时就会触发自动加载机制。提供最后一次机会可以把类加载过来。
    具体场景：
    New 类，类::静态方法， 类 extends 类2 类2不存,
    sql_autoload_register();更加强大
    不能通过类名直接找到类，不知道到那个目录找，因为有多个类的存在。？？？ 给每个类增加命名空间和该类位置有一定关联，就可以给出位置找到类。
    1. 入口文件不用命名空间，入口文件只用来加载，不会再文件中定义类。
    2. 每个类的命名空间包含当前类所在路径。如：namespace framework\core;
        项目类等如果可以更改的地方，就不需要携带application 所以项目类的命名空间定义为：namespace admin\controller; 最终类路径可以__DIR__.namespace;
    3. 第三方类不用增加命名空间，作为一个特例处理（手动require）。

4.4.2 给每个类增加命名空间
    controller 和 model 中的命名空间一样，最主要原因在于内部模块理论上不存在重名情况。在此时命名空间主要作用是体现类的位置。加入空间修饰防止重名作用次之。

4.4.3 根据类的解析出完整路径,在入口文件中使用命名空间。使用类时需要指出使用的是哪个命名空间的类。

4.4.4 完成自动加载
    根据提示的需要的类名解析出类所在的路径完成类的加载
    1. 根据\将命名空间字符串分割成数组
        命名空间定义跟路径相关
        项目相关：admin\controller  home\controller
        框架相关：framework\controller
    2. 再将分隔开的数组合并成一个路径
    
4.4.5 封装入口文件
    入口文件封装需求，因为采用oop思想封装的框架，基本单元为类。框架里面的代码都应该是类。

5.0 错误调试：
    Warning: require_once(./framework/core/Smarty.class.php): failed to open stream: No such file or directory
    此错误来源是，在基础控制类Controller.class.php中new Smarty而来。使用一个类的时候是在当前空间去找，所以使用时需要加入名称修饰。因此使用全局空间'\'来修正。
    继续测试：
    require_once(./application/Smarty_Autoloader.class.php): failed to open stream: No such file or directory
    当需要使用 Smarty的时候报错误。
    解决办法：Smarty_Autoloader是smarty自己加载，也不是我们自己定义的类。因为触发自动加载机制，因此报错。

5.1 最终入口文件自动加载

5.2 复习入口文件
    $m = isset($_GET['m'])?$_GET['m']:'home';
    $c = isset($_GET['c'])?$_GET['c']:'home';
    $a = isset($_GET['a'])?$_GET['a']:'home';

    $controller_name = $c.'Controller';
    $class_file = './application/'.$m.'/controller/'.$controller_name;

    require_once $class_file;
    $controller = new $controller_name;
    $controller->$a();

    使用命名空间跟路径关联进行类定位
    namespace framework\core; home\contrller;
    解析命名空间找到类路径再进行文件加载
    explode('\\', $className);
    拼接$class_file_path = str_replace('\\', '/', $className);
    使用spl_autoload_register()进行自动加载。

6.1 $model = Factory::M('admin\model\UserModel');
    使用时需要告诉工厂，使用的是哪个方法。

6.2 作业数据，user查询模板展示完成。

7.1 配置系统
    配置文件就是用来保存一些固定格式，多文件之间的公共数据。
    以后文件的路径、名称等可变数据只需要通过配置文件更改即可
    1. 框架基本信息，如：框架的版本，模板定界符，模板路径，数据库链接等
    2. 项目基本信息，如：项目名称，项目目录，项目开发数据库等。
    3. 模块基本信息，如：模块名称，模块数据等。
    优先级：
    前台后台>项目信息>框架配置;y优先级高的覆盖优先级低的配置项
    使用：array_merge()合并数组函数，覆盖相同的，整合不相同的。

7.2 增加路径常量
    框架中的路径，方便管理使用常量配置


8.1 自动化处理，在基础模型类中，把sql 语句的公共部分封装起来。
如：INSERT INTO 表名（`字段`， `字段`） VALUES（'值','值'）;
    DELETE FROM 表名 WHERE 主键字段=值
    UPDATE 表名 SET 字段1='值1'，字段2='值2';
    SELECT 字段1，字段2，FROM 表名 WHERE 字段='值'

8.2 完善模型类，在controller 中使用模型是不用每次传递model。因为模型默认是以Model.class.php结尾，所以不用每次都写UserModel

10 项目需求分析
    1. 会根据想法（大概什么东西）————》产品经理制定细节————》美工会根据需求，制作出效果图————》web前端将效果制作成html+css+javascript页面————》后端根据效果图制作后台真实数据管理。
    2. 根据html页面分析需求：
    理论上一个前端网页都应该能够在后台进行维护。
    3. 根据要求，对项目进行分工：
    用户模块管理登录，注册。
    分类模块管理项目的分类的增删改查。
    文章模块管理 文章的增删改查。
    评论管理：   发表，回复，审核。
10.0.1 表之间的关系
    1. 数据表之间存在 一对一的关系，一对多，多对一，多对多的关系。
    一张表对应一张表：通常经常查询的数据保存到一张表，不经常查询的数据保存到另一张表。

    一对一关系表：
    员工信息表：user_id; user_name; pwd; 身份证; 籍贯; 手机号; 紧急联系人; 邮箱; qq号; 微信号; 银行卡; 入职时间; 
    员工基本信息表： 序号; user_id; user_name; pwd; 
    员工详细信息表： 身份证; 籍贯; 手机号; 紧急联系人; 邮箱; qq号; 微信号; 银行卡; 入职时间; 

    一对多关系表：
    品牌表：   小米     苹果      三棵松树
    商品分类：cat_id; cat_name; parent_id
                1       手机      0
                2       食品      0
    商品表:   goods_id; goods_name; cat_id
                1        小米手机     1
                2        苹果手机     1

    多对多关系表：通常会借助第三张表实现关系展示
    教师表： teacher_id;  t_name;  
                1           老张
                2           老王
                3           老秦

    学生表： student_id;  s_name;
                1           张三
                2           李四
                3           王五

    关系表: student_teacher
            st_id;   s_id;     t_id
              1        1        1
              2        2        1
              3        3        2
              4        2        3
              5        3        2


10.1 项目规范
    文件名：采用大驼峰法（首字母大写）:UserModel.class.php
    类名： 采用大驼峰发（首字母大写）：class UserModel
    方法名：小驼峰发（第二个单词开始大写）userAction 或 下划线 user_action

    编码注释：
    通过注释很快能知道类、函数作用
    文件编码：utf-8
    头信息：header("Content-type:text/html; charset=utf-8)
    数据库编码：utf8

10.2 项目开发技巧
    11163口诀：
    1 —— 1个功能模块对应一个控制器。
    1 —— 1个控制器通常操作一个模型。
    1 —— 1个模型类操作一个数据表。
    6 —— 1个控制器中通常有6个方法：
        indexAction: 用来展示列表页面
        addAction: 用来展示添加内容的表单页面
        addHandleAction: 将表单提交到这里，接收数据然后保存到数据库
        editAction: 显示编辑的表单页面
        updateAction: 接收提交的保单数据并更新数据表
        deleteAction: 接收删除数据，并删除数据
    3 —— 指的是3个视图文件
        index.html: 显示内容列表
        add.html: 显示添加内容的表单
        edit.html: 显示修改内容的表单

10.3 数据库建表过程其实就是考察业务逻辑
    注册、登录表：用户表 ask_user
    字段        类型      中文名称       是否主键     备注
   user_id      int       用户序号       true
   username  varchar(32)  用户名                    不能为空
   password  varchar(64)  密码                      不能为空
   email     varchar(32)  邮箱
   phone     char(15)     手机号                    不能为空
   member    tinyint      是否会员                 0——未开通
                                                  1——开通
   is_action tinyint      是否激活                 0表示：未激活
                                                   1表示：已激活
                                                   2表示:其他状态
    reg_time  int          注册时间
    user_pic  varchar(28)  用户头像
    
    问题表：ask_question
        字段        类型      中文名称       是否主键     备注
    question_id     int      问题序号         true
    question_title  varchar(128) 问题标题
    question_desc   text      问题描述
    cat_id          tinyint   问题分类
    topic_id        int       所属话题
    user_id         int       用户id
    pub_time        data      发布时间
    focus_num       int       关注数量
    reply_num       int       回复数量
    view_num        int       浏览数量

    话题和问题关系：ask_question_topic
    qt_id   int 序号    true
    question_id    int      问题序号
    topic_id    int     话题序号


    分类表：ask_category
        字段        类型      中文名称       是否主键     备注
    cat_id          int       分类序号      true
    cat_name        varchar(32)分类名称
    cat_logo        varchar(128)分类图标
    cat_desc        varchar(32) 分类描述
    parent_id       int        父级分类

    会员表：ask_member
    member_id   int     会员序号    true
    start_time  data    开通时间
    end_time    data    结束时间
    user_id     int     用户序号

    话题表：ask_topic
    topic_id    int     话题序号    true
    topic_title     varchar(128)    话题标题
    topic_desc      text     话题描述
    topic_pic   varchr(128)     话题图片

10.4 关于文件路径的说明：
    web网络中传输文件的时候，为安全考虑，一般是没有服务器根目录权限的。
    不能从服务器根目录出发进行上传；只能从当前目录出发。
    读取的时候，是没有权限限制，一般没有权限限制，可以从服务器根目录进行出发读取。
    定义上传文件路径是，不能使用 / 根目录的绝对路径

10.5 递归无限极分类查询
    //无限极分类查询
    /**
    * php
    * --|thinkphp
    * --|--|tp3.2
    * --|--|tp5
    * --|--|--|模板引擎
    * --|laravel
    * web前端
    * --|js
    * --|bootstrap
    * 先查询 php 的所有子类，保存到一个数组中，再查询属于thinkphp的（parent_id = php），保存到一个数组中。以此层级查询。
    */
    //递归查询所有分类信息
    //@cat_list 查询数组
    //@parent_id 查询那个分类下面的子类，默认值是0，是要查询顶级分类下的子类
    public function getTreeCategory($cat_list, $p_id= 0){
        static $arr = array();
        foreach ($cat_list as $k=>$v){
            //谁的parent_id = 0 ,就是0的子类
            if ($v['parent_id'] == $p_id) {
                $arr[] = $v;
                //继续查询第下一层子类
                $this->getTreeCategory($cat_list, $v['cat_id']);
            }
        }
        return $arr;
    }
    最后在，查出的数组中，添加一个字符串用来展示分级。
    提示：1.每递归查询1次，前面空的数量+1.
          2.输出空格可以使用 smarty的indent变量调节器
          indent:输出的空格数量:表示字符
          3.把每一个分类钱的空格数量保存起来，可以便利使用

10.6 使用php做简单验证，复杂验证需要借助正则表达式和javascript
    简单验证：
    1.分类标题和描述不能为空；
    2.分类标题不能是数字，或数字开头的；
    3.标题和描述不能超过12个字符；同一个分类下不能创建重复的子类；strlen函数只统计字节数，中文、英文字符的个数。

11.0 话题管理
    11163
    1. 1个功能模块对应一个控制器。
    1. 1个控制器对应一个操作模型。
    1. 1个模型对应一张数据表。
    6. 1个控制器应该包含最少6个功能，展示， 添加，添加中，编辑，编辑中，删除，
    3. 3个视图文件，展示，添加，编辑

11.1 在问题列表的信息，一条记录需要查询 ask_question ask_user ask_category 三个数据表
    问题表：ask_question 
    分类表：ask_category
    用户表: ask_user
