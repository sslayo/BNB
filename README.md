# PHP Binance API
This project aims to provide an easy method to making API calls to Binanace. Any questions or bugs please feel free to contact me

#### Installation
```
include 'binance_lib.php';
```

#### Getting started
```php
setKeys("vmPUZE6mv9SD5VNHk4HlWFsOr6aKE2zvsw0MuIgwCIPy6utIco14y7Ju91duEh8A", "NhqPtmdSJYdKjVHjA7PZj4Mge3R5YNiP1e3UZjInClVN65XAbvqqM6A7H5fATj0j");
```
#### Getting latest price of a symbol
```php
//Symbol required         
getMarket("BNBBTC");
```
<details>
 <summary>View Response</summary>

```
Price of BNBBTC: 0.00025287
```
</details>

#### Getting latest price of a entire market, or each sub market
```php
getMarkets();  // Default will show all markets 
getMarkets("BTC");
getMarkets("ETH");
getMarkets("USDT");
```
<details>
 <summary>View Response</summary>

```
BTC MARKETS
ETHBTC - 0.05940000
LTCBTC - 0.01134800
BNBBTC - 0.00025287
NEOBTC - 0.00498600
BCCBTC - 0.05554900
GASBTC - 0.00370000
HCCBTC - 0.00000180
HSRBTC - 0.00289000
ELCBTC - 0.00000053
MCOBTC - 0.00134600
WTCBTC - 0.00108400
LLTBTC - 0.00001669
LRCBTC - 0.00001100
QTUMBTC - 0.00191300
YOYOBTC - 0.00000481
OMGBTC - 0.00137200
ZRXBTC - 0.00003410
STRATBTC - 0.00052700
SNGLSBTC - 0.00002691
BQXBTC - 0.00010600
KNCBTC - 0.00019401
FUNBTC - 0.00000535
SNMBTC - 0.00001731
IOTABTC - 0.00007957
LINKBTC - 0.00007774
XVGBTC - 0.00000092
CTRBTC - 0.00015228
SALTBTC - 0.00051600
MDABTC - 0.00027280
MTLBTC - 0.00130000
SUBBTC - 0.00001829
EOSBTC - 0.00010102
SNTBTC - 0.00000498
ETCBTC - 0.00211300
MTHBTC - 0.00001501
ENGBTC - 0.00010582
DNTBTC - 0.00000823
ZECBTC - 0.00000000
ETH MARKETS
QTUMETH - 0.03200100
EOSETH - 0.00170800
SNTETH - 0.00008380
BNTETH - 0.00693500
BNBETH - 0.00425002
BTMETH - 0.00018900
OAXETH - 0.00136600
DNTETH - 0.00013895
MCOETH - 0.02268100
ICNETH - 0.00408300
WTCETH - 0.01825000
LRCETH - 0.00016311
OMGETH - 0.02280100
ZRXETH - 0.00057810
STRATETH - 0.00854300
SNGLSETH - 0.00044025
BQXETH - 0.00177000
KNCETH - 0.00328010
FUNETH - 0.00009293
SNMETH - 0.00028500
NEOETH - 0.08591000
IOTAETH - 0.00132103
LINKETH - 0.00129075
XVGETH - 0.00001521
CTRETH - 0.00259290
SALTETH - 0.00860000
MDAETH - 0.00459000
MTLETH - 0.02700000
SUBETH - 0.00030387
ETCETH - 0.03441900
MTHETH - 0.00025301
ENGETH - 0.00177500
ZECETH - 0.00000000
USDT MARKETS
BTCUSDT 5642.52000000
ETHUSDT 337.01000000
```
</details>

#### Getting latest price of a symbol
```php
//Symbol required
getDepth("BNBBTC"); // If left blank BNBBTC is default
```
<details>
 <summary>View Response</summary>

```
Will draw a depth chart
```
</details>

#### Setting Limit Orders
```php
//Symbol, Quantity, Price, timeInForce
buyLimitOrder("BNBBTC", 50, 0.00002, "GTC"); // Default GTC if not entered, or can change it IOC 
sellLimitOrder("BNBBTC", 50, 0.00002, "GTC"); // Default GTC if not entered, or can change it IOC 
```
<details>
 <summary>View Response</summary>

```
returned json
```
</details>

#### Setting Market Orders
```php
//Symbol, Quantity
buyMarketOrder("BNBBTC", 50);
sellMarketOrder("BNBBTC", 50);
```
<details>
 <summary>View Response</summary>

```
returned json
```
</details>

#### Check Order Status
```php
//Symbol, OrderID
orderStatus("BNBBTC", 456454);
```
<details>
 <summary>View Response</summary>

```
{
		  "symbol": "LTCBTC",
		  "orderId": 1,
		  "clientOrderId": "myOrder1",
		  "price": "0.1",
		  "origQty": "1.0",
		  "executedQty": "0.0",
		  "status": "NEW",
		  "timeInForce": "GTC",
		  "type": "LIMIT",
		  "side": "BUY",
		  "stopPrice": "0.0",
		  "icebergQty": "0.0",
		  "time": 1499827319559
		}
```
</details>

#### Check Open Orders
```php
//Symbol, OrderID
openOrders("BNBBTC", 456454);
```
<details>
 <summary>View Response</summary>

```
[
		  {
		    "symbol": "LTCBTC",
		    "orderId": 1,
		    "clientOrderId": "myOrder1",
		    "price": "0.1",
		    "origQty": "1.0",
		    "executedQty": "0.0",
		    "status": "NEW",
		    "timeInForce": "GTC",
		    "type": "LIMIT",
		    "side": "BUY",
		    "stopPrice": "0.0",
		    "icebergQty": "0.0",
		    "time": 1499827319559
		  }
		]
```
</details>

#### ALL orders; active, canceled or filled
```php
allOrders();
```
<details>
 <summary>View Response</summary>

```
[
		  {
		    "symbol": "LTCBTC",
		    "orderId": 1,
		    "clientOrderId": "myOrder1",
		    "price": "0.1",
		    "origQty": "1.0",
		    "executedQty": "0.0",
		    "status": "NEW",
		    "timeInForce": "GTC",
		    "type": "LIMIT",
		    "side": "BUY",
		    "stopPrice": "0.0",
		    "icebergQty": "0.0",
		    "time": 1499827319559
		  }
		]
```
</details>


#### Check Order History
```php
//Symbol, OrderID
orderHistory("BNBBTC"); // BNBBTC is default if left blank
```
<details>
 <summary>View Response</summary>

```
returned json
```
</details>

#### Cancel Order 
```php
//Symbol, OrderID
cancelOrder("BNBBTC", 456454);
```
<details>
 <summary>View Response</summary>

```
{
		  "symbol": "LTCBTC",
		  "origClientOrderId": "myOrder1",
		  "orderId": 1,
		  "clientOrderId": "cancelMyOrder1"
		}
```
</details>

#### Current Position 
```php
currentPosition();
```
<details>
 <summary>View Response</summary>

```
returned json
```
</details>
