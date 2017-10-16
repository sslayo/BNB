# PHP Binance API
This project is designed to help you make your own projects that interact with the [Binance API](https://www.binance.com/restapipub.html). You can stream candlestick chart data, market depth, or use other advanced features such as setting stop losses and iceberg orders. This project seeks to have complete API coverage including WebSockets.

#### Installation
```
npm install node-binance-api --save
```

#### Getting started
```javascript
const binance = require('node-binance-api');
binance.options({
	'APIKEY':'<key>',
	'APISECRET':'<secret>'
});
```
