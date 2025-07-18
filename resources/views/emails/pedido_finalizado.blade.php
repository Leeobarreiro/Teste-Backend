<!DOCTYPE html>
<html>
<body>
  <h1>Pedido finalizado</h1>
  <p>Obrigado pelo seu pedido, {{ $pedido->email }}!</p>
  <p>Total: R$ {{ $pedido->total }}</p>
</body>
</html>
