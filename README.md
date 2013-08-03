Heartbeat API Demo
=====================

Demonstration of some features of the new Heartbeat API in WordPress 3.6

Default interval is 15 seconds.  When the current window loses focus it changes to 120 seconds.

#### jQuery Events:

* heartbeat-connection-lost - Connection error, has one parameter [error], contains error message information
* heartbeat-connection-restored - Connection has been restored AFTER a connection lost error
* heartbeat-send - Information is sent to the server, has one parameter [data] that contains the data to be sent, can be modified via the event
* heartbeat-nonces-expired - nonces have expired
* heartbeat-tick
* heartbeat-error

#### Notable methods

* interval( speed, ticks ) - used to get or set the interval speed.
    * **speed** - string - “fast”, “slow” or “long-polling”
      * *fast* - 5 seconds
      * *slow* - 60 seconds
      * *long-polling* - experimental, not sure what it does for now
  - **ticks** - integer - number of ticks before it returns to the current default pace.  maximum value of 30 is accepted
  - **returns** - integer - the current value of the interval
* enqueue( handle, data, dont_overwrite ) - enqueue data to be sent on the next available heartbeat
    * **handle** - string - unique identifier for the data.  used by isQueued and by the receiving PHP
    * **data** - mixed - data that will be sent
    * **dont_overwrite** - boolean - whether or not to overwrite any data that already exists with an identical handle
    * **return** - boolean - whether or not the data was successfully enqueued
* isQueued( handle ) - check if handle with data is enqueued
    * **handle** - string - unique ID for the data to be checked
    * **return** - mixed - the data matching the handle or null
* hasConnectionError - checks if there is a connection error currently
    * **return** - boolean
