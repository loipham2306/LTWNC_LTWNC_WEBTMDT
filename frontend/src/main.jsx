import React from 'react'
import ReactDOM from 'react-dom/client'
import App from './App.jsx'

// Tự động nạp Bootstrap đã cài qua npm vào toàn bộ dự án
//import 'bootstrap/dist/css/bootstrap.min.css'
import './assets/css/bootstrap.min.css'
import './assets/css/style.css'

ReactDOM.createRoot(document.getElementById('root')).render(
    <App />
)