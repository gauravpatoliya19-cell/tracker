->withMiddleware(function (Middleware $middleware) {
    // આ લાઇન Render ના સાચા IP ને પકડવા માટે ફરજિયાત છે
    $middleware->trustProxies(at: '*'); 
})
