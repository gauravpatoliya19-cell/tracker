#!/bin/bash

# ૧. ડેટાબેઝ ક્લીન કરીને નવું ટેબલ બનાવશે (બધી એરર સોલ્વ કરવા માટે)
php artisan migrate: --force

# ૨. લાર્વેલ સર્વર ચાલુ કરશે
php -S 0.0.0.0:$PORT -t public
