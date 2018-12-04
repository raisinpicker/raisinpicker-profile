# Raisinpicker Installation Profile for Drupal 8.x

Learn more on https://raisinpicker.github.io/


## Sub-module rp_scoring

Calculate a "relevance score" for reach content node based on a given ruleset.

Current rules:
* # of views
* # of edits
* length (i.e. # of characters): 

Calculated key figures (per node):
* Relevance index: Calculates
* Relevance score: Relevance index * time passed since last view

## Sub-module rp_points

Users collect points for activities.

Current rule set:
* 3 point for creating new content
* 3 point for editing content
* 1 point for reviewing content

Calculated key figures (per user):
* All-time user score: sum of all points
* Current score: points last 7 days + 50% last 30 days + 5% last 180 days

## Sub-module rp_schedule

Schedules reviews of content nodes.

User settings:
* Review periodicity: 2weeks, 1month, 3months, 6months, 1year, 2years
* Channel: Email, IFTTT
