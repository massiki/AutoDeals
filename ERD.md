# ERD AutoDeals — Car Dealership Management System

## Tabel

### cars

| Field | Type | Constraints |
|---|---|---|
| id | bigint | PK, Auto Increment |
| stock_code | varchar(255) | UNIQUE, Auto-generated (`MOB-XXXX`) |
| brand | varchar(255) | |
| model | varchar(255) | |
| year | integer | ≤ tahun sekarang |
| price | decimal(15,2) | ≥ 0 |
| kilometer | integer | |
| color | varchar(255) | |
| transmission | varchar(255) | Enum: Manual, Automatic, CVT |
| fuel_type | varchar(255) | Enum: Bensin, Diesel, Hybrid, Electric |
| engine_cc | integer | NULLABLE |
| plate_number | varchar(255) | NULLABLE |
| condition | varchar(255) | Enum: New, Excellent, Good, Fair, Poor |
| vin | varchar(255) | UNIQUE, NULLABLE |
| description | text | NULLABLE |
| photos | json | NULLABLE, max 10 |
| status | varchar(255) | Default 'Available'. Enum: Available, Reserved, Sold |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### inquiries

| Field | Type | Constraints |
|---|---|---|
| id | bigint | PK, Auto Increment |
| car_id | bigint | FK → cars.id, NULLABLE, ON DELETE SET NULL |
| buyer_name | varchar(255) | |
| phone | varchar(255) | |
| email | varchar(255) | |
| inquiry_date | datetime | |
| offer_price | decimal(15,2) | NULLABLE |
| status | varchar(255) | Default 'Pending'. Enum: Pending, Test Drive, Approved, Rejected |
| notes | text | NULLABLE |
| created_at | timestamp | |
| updated_at | timestamp | |

---

## Relasi

```
cars 1────N inquiries
(Car has many inquiries)
(Inquiry belongs to Car)
```

## Diagram Relasi

```
┌──────────────┐       ┌────────────────┐
│     cars     │       │   inquiries    │
├──────────────┤       ├────────────────┤
│ id (PK)      │◄──────│ car_id (FK)    │
│ stock_code   │       │ id (PK)        │
│ brand        │       │ buyer_name     │
│ model        │       │ phone          │
│ year         │       │ email          │
│ price        │       │ inquiry_date   │
│ kilometer    │       │ offer_price    │
│ color        │       │ status         │
│ transmission │       │ notes          │
│ fuel_type    │       └────────────────┘
│ engine_cc    │
│ plate_number │
│ condition    │
│ vin          │
│ description  │
│ photos       │
│ status       │
│ created_at   │
│ updated_at   │
└──────────────┘
```

## Enum Values

### Car Status
- `Available` — Tersedia untuk dijual
- `Reserved` — Sudah ada DP / reservasi
- `Sold` — Terjual

### Car Condition
- `New` — Baru (0 km)
- `Excellent` — Kondisi sangat baik
- `Good` — Kondisi baik
- `Fair` — Kondisi cukup
- `Poor` — Kondisi perlu perbaikan

### Transmission
- `Manual` — Transmisi manual
- `Automatic` — Transmisi otomatis konvensional
- `CVT` — Continuously Variable Transmission

### Fuel Type
- `Bensin` — Bensin (pertalite/pertamax)
- `Diesel` — Solar
- `Hybrid` — Hybrid (bensin + listrik)
- `Electric` — Listrik murni

### Inquiry Status
- `Pending` — Belum diproses
- `Test Drive` — Jadwal test drive
- `Approved` — Disetujui / deal
- `Rejected` — Ditolak / batal
