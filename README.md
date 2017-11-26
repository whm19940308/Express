# Express
快递公司，只要直接输入快递单号就可以自动识别快递单号所在快递公司和物流信息，还是非常方便的，只要几行代码就可以完美的集成到你系统的功能中了！

使用示例：
使用如下，只需要调用类中的getLogisticsInfo()方法，参数传入订单号即可
$e = new Express();
$data = $e->getLogisticsInfo("453371918456");
 

var_dump($data);
