## Craft WorldTime.io plugin

Uses [WorldTime.io](http://www.worldtime.io/) to fetch timezone information about the visitors ip. If a valid IP address can't be detected, it tries to look it up through selize.com

### Usage

```twig
{% set timestats = craft.worldtime.ipToTime() %}

<p>
    Country: {{ timestats.location.region }}<br/>
    Local time: {{ timestats.summary.local }}<br/>
</p>
```

Example return:

```json
{
  "current": {
    "abbreviation": "GMT",
    "description": "Greenwich Mean Time",
    "utcOffset": "+6:30",
    "effectiveUntil": "2012-03-25 01:00:00",
    "isDst": true
  },
  "location": {
    "latitude": 51.02314,
    "longitude": -0.13343543,
    "region": "United Kingdom"
  },
  "summary": {
    "hasDst": true,
    "local": "2011-11-24 18:40:13",
    "utc": "2011-11-24 18:40:13"
  },
  "url": "http://worldtimeengine.com/current/51.02314_-0.13343543",
  "version": 1.2,
  "next": {
    "abbreviation": "GMT",
    "description": "Greenwich Mean Time",
    "utcOffset": "+6:30",
    "effectiveUntil": "2012-03-25 01:00:00",
    "isDst": true
  }
}
```

You may also define a IP address to look up:

```twig
{% set timestats = craft.worldtime.ipToTime('216.58.209.142') %}
```
