# ERD AutoDeals – Car Dealership Management System

## Tabel

### users

| Field | Type |
|---|---|
| id | bigint |
| name | string |
| email | string |
| password | string |
| gender | string |
| phone | string |
| photo | string |
| created_at | timestamp |
| updated_at | timestamp |

---

### court_bookings

| Field | Type |
|---|---|
| id | bigint |
| court_id | int |
| user_id | int |
| total_hours | int |
| total_tax_amount | int |
| price_per_hour_amount | int |
| grand_total_amount | int |
| status | string |
| proot_of_payment | string |
| start_at | datetime |
| end_at | datetime |
| created_at | timestamp |
| updated_at | timestamp |
---

### court_booking_stots

| Field | Type |
|---|---|
| id | bigint |
| slot_at | datetime |
| court_id | int |
| court_booking_id | int |
| status | string |
| created_at | timestamp |
| updated_at | timestamp |

---

### courts

| Field | Type |
|---|---|
| id | bigint |
| name | string |
| about | text |
| thumbnail | string |
| category_id | int |
| city_id | int |
| photos | json |
| features | json |
| phone | string |
| material | string |
| price | int |
| status | string |
| address | text |
| created_at | timestamp |
| updated_at | timestamp |

---

### cities

| Field | Type |
|---|---|
| id | bigint |
| name | string |
| photo | string |
| created_at | timestamp |
| updated_at | timestamp |

---

### categories

| Field | Type |
|---|---|
| id | bigint |
| name | string |
| photo | string |
| created_at | timestamp |
| updated_at | timestamp |

---

### court_time_slots

| Field | Type |
|---|---|
| id | bigint |
| court_id | int |
| day_of_week | int |
| start_time | time |
| end_time | time |
| created_at | timestamp |
| updated_at | timestamp |

---
