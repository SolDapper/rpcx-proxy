# rpcx-proxy
solana rpc proxy for non-node enviroments running php

# features
whitelist domains or allow all origins

protects your rpc endpoint from public view

# example usage
just send your rpc requests from your front end to the proxy
```javascript
let proxy = "https://yourwebsite.com/proxy.php";
let connection = new solanaWeb3.Connection(proxy, "confirmed");
```
