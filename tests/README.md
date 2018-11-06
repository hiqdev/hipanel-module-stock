# Acceptance testing plan

Short list functionality

## Models

- sidebar menu (✓)
    - link to index for admins but not clients [admin/StockSidebarMenuCest], [client/StockSidebarMenuCest] \(✓\)
- index page (✓)
    - filtering (✓)
        - by brand (✓)
    - sorting (✓)
        - by type (✓)
    - legend block (✓)
        - has 3+ elements (✓)
- details page (✓)
    - object info (✓)
        - title (✓)
        - detailed info (✓)
    - parts list (✓)
        - proper number (✓)
- update (✓) 
    - single/bulk update from details/index page (✓)
        - input data (✓)
        - returns to corresponding page (✓)
        - proper result (✓)
- create (✓)
    - create more then one object (✓)
    - returns to corresponding page (✓)
- delete
- copy (✓)

## Parts

- sidebar menu (✓)
    - link to index for admins but not clients [admin/StockSidebarMenuCest], [client/StockSidebarMenuCest] \(✓\)
- index page (✓)
    - filtering (✓)
        - by brand (✓)
    - sorting (✓)
        - by serial (✓)
    - legend block (✓)
        - has 3+ elements (✓)
- details page (✓)
    - object info (✓)
        - title (✓)
        - detailed info (✓)
    - parts list (✓)
        - proper number (✓)
- update (✓)
    - single/bulk update from details/index page (✓)
        - input data (✓)
        - returns to corresponding page (✓)
        - proper result (✓)
- create (✓)
    - create more then one object (✓)
    - returns to corresponding page (✓)
- delete
- copy (✓)

## History
- sidebar menu (✓)
    - link to index for admins but not clients [admin/StockSidebarMenuCest], [client/StockSidebarMenuCest] \(✓\)
- index page (✓)
    - filtering (✓)
        - by client (✓)
    - sorting (✓)
        - by time (✓)
    - legend block (✓)
        - has 3+ elements (✓)

## Model groups
- sidebar menu (✓)
    - link to index for admins but not clients [admin/StockSidebarMenuCest], [client/StockSidebarMenuCest] \(✓\)
- index page (✓)
    - filtering (✓)
        - by name (✓)
    - sorting (✓)
        - by id (✓)
- update (✓)
    - single/bulk update from details/index page (✓)
        - input data (✓)
        - returns to corresponding page (✓)
        - proper result (✓)
- create (✓)
    - create more then one object (✓)
    - returns to corresponding page (✓)
- delete (✓)
- copy (✓)

[admin/StockSidebarMenuCest]:       acceptance/admin/StockSidebarMenuCest.php
[client/StockSidebarMenuCest]:      acceptance/client/StockSidebarMenuCest.php
