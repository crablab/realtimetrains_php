# Documentation

## Configuration

## Setup

## Realtime Trains Documenation

As this wrapper is for the RTT API, it is of course based on their documentation. If it isn't in their docs, then this wrapper won't support it. You can find the docs [here](http://www.realtimetrains.co.uk/api/pull).

## Methods

### A note about formatting

These docs are designed to be easy to understand, and are based off the code. If it doesn't match or doesn't work, create an issue. Even better, find the issue and make a PR. I like PRs. 

- `$value` a value is expected as a parameter, it is not optional
- `$value2="string"` a value can be inserted as a parameter, it is optional and defaults to a string of value _string_

### `locationList`

Arguments: 

 - `$crs` the CRS code of the station to search 
 - `$type=null` filter by _departures_ or _arrivals_
 - `$to=null` CRS to filter destination by
 - `$from=null` CRS to filter origina by
 - `$timestamp=null` a specific timestamp to filter services by
 - `$rows=20` maximum rows returned

 Returns: 

 An object matching the API documentation. 