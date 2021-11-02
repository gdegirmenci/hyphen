# Hyphen

The project is for deciding a discount strategy.

[![Run Tests](https://github.com/gdegirmenci/hyphen/actions/workflows/run-tests.yml/badge.svg)](https://github.com/gdegirmenci/hyphen/actions/workflows/run-tests.yml)
[![StyleCI](https://github.styleci.io/repos/423194269/shield?branch=main&style=flat)](https://github.styleci.io/repos/423194269?branch=main)

## About
For now, there are three possible ways of getting a discount:

- A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
- For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
- If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.

## Install

Clone the project

```bash
git clone git@github.com:gdegirmenci/hyphen.git
```

Build docker images and up containers

```bash
docker-compose build && docker-compose up -d
```

Enter to the container

```bash
docker exec -it hyphen_php bash
```

Give necessary permission to storage

```bash
chmod -R 777 storage/
```

Copy .env.example as .env

```bash
cp .env.example .env
```

Install dependencies

```bash
composer install
```

Generate application key

```bash
php artisan key:generate
```

Migrate schemas

```bash
php artisan migrate
```

Add application URL to host file

```bash
127.0.0.1 hyphen.local
```

## Configure

To configure application, thresholds and category ids should be provided in environment file `.env` for each strategy. 

```bash
TOTAL_PRICE_BASED_DISCOUNT_STRATEGY_THRESHOLD=1000      # What is the minimum limit for getting discount?
TOTAL_PRICE_BASED_DISCOUNT_STRATEGY_DISCOUNT=10         # What is the discount percentage?

CATEGORY_BASED_DISCOUNT_STRATEGY_THRESHOLD=5            # How many items should be there to get discount?
CATEGORY_BASED_DISCOUNT_STRATEGY_CATEGORY_ID=2          # Which category should be eligible for discount?

PRODUCT_COUNT_BASED_DISCOUNT_STRATEGY_THRESHOLD=2       # How many items should be there to get discount? 
PRODUCT_COUNT_BASED_DISCOUNT_STRATEGY_DISCOUNT=20       # What is the discount percentage?
PRODUCT_COUNT_BASED_DISCOUNT_STRATEGY_CATEGORY_ID=1     # Which category should be eligible for discount?
```

## Usage

### **REST API**

To get a discount, `/api/discount` endpoint should be called as mentioned below. 

```bash
## Get discount
curl -X "POST" "http://hyphen.local/api/discount" \
     -H 'Content-Type: application/json' \
     -d $'{
  "id": "1",
  "customer-id": "1",
  "items": [
    {
      "quantity": "10",
      "product-id": "B102",
      "unit-price": "4.99",
      "total": "49.90"
    }
  ],
  "total": "49.90"
}'
```

## How & Why

### Strategies

Since we have different ways to get discounts, strategy pattern could be useful to decide which strategy should be followed to apply a discount.   

According to the thresholds and given category ids, where we are going to mention environment file, application would decide a discount for each strategy.

It means that, also possible to apply multiple discounts on an order.

### Possible Iterations

- Every strategy is loading from application itself. Instead of keeping business logic inside codebase, it could be separated as module and install via composer.  
- Configurations are defined in environment file. Instead of keeping values inside environment file, these values could be migrated to database. 
- Application is trying to apply multiple discounts at one runtime with created strategy pattern. Instead of this, factory pattern and recurring methods could be used. 
- Strategies could have a flag as active / deactive. 
