#!/usr/bin/env python3

import time
import bme680
import sqlite3

# Constants
PROBING_TIME = 600
DB = '/home/pi/weather/database.db'
TAB = 'weather'

# Connect to sensor
try:
    sensor = bme680.BME680(bme680.I2C_ADDR_PRIMARY)
except (RuntimeError, IOError):
    sensor = bme680.BME680(bme680.I2C_ADDR_SECONDARY)

# Connect to sqlite3
conn = sqlite3.connect(DB)

# Create cursors
cur = conn.cursor()

# Check if table exists
tab_exists = cur.execute("SELECT COUNT(name) FROM sqlite_master WHERE type='table' AND name='{}';".format(TAB)).fetchone()[0]

# Create table if needed
if tab_exists == 0:
    cur.execute("CREATE TABLE {} (timestamp, temp, pres, hum);".format(TAB))

# These oversampling settings can be tweaked to
# change the balance between accuracy and noise in
# the data.
sensor.set_humidity_oversample(bme680.OS_4X)
sensor.set_pressure_oversample(bme680.OS_4X)
sensor.set_temperature_oversample(bme680.OS_4X)
sensor.set_filter(bme680.FILTER_SIZE_3)

# Prepare query
sql_insert_query = "INSERT INTO {} ".format(TAB) + "VALUES ('%s', %.2f, %.2f, %.2f);"

# Iterate loop
while True:
    # Probe data
    if sensor.get_sensor_data():
        # Get timestamp
        timestamp = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())
        # Set data point
        query = sql_insert_query % (timestamp, sensor.data.temperature, sensor.data.pressure, sensor.data.humidity)
        # Execute query
        cur.execute(query)
        # Commit change
        conn.commit()
        # Sleep
        time.sleep(PROBING_TIME)