# BUSCO Public API Contract

Base URL: `{API_URL}/api` (Render Laravel backend)

All responses are JSON. List endpoints return Laravel pagination metadata.

## Pagination envelope

```json
{
  "data": [],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 6,
    "total": 15,
    "from": 1,
    "to": 6
  },
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  }
}
```

## GET /api/home

```json
{
  "latest_news": [NewsListItem],
  "active_quedan": QuedanItem | null,
  "previous_quedan": QuedanItem | null
}
```

## GET /api/news

Query: `category`, `page`

Returns paginated `NewsListItem[]`.

## GET /api/news/{id}

Returns `{ "data": NewsDetail }` or 404 if draft/missing.

## GET /api/quedan

Query: `page`

```json
{
  "active_price": QuedanItem | null,
  "previous_price": QuedanItem | null,
  "history": Paginated<QuedanHistoryItem>
}
```

## GET /api/careers

Query: `search`, `department`, `employment_type`, `page`

Returns paginated `JobListItem[]` plus `filters` and `departments`.

## GET /api/careers/{slug}

Returns `{ "data": JobDetail, "related_jobs": JobListItem[] }` or 404.

## Types

### NewsListItem

| Field | Type |
|-------|------|
| id | integer |
| title | string |
| sub_title | string \| null |
| excerpt | string |
| category | string |
| image_url | string |
| is_featured | boolean |
| created_at | ISO 8601 string |

### NewsDetail

NewsListItem plus `content`, `gallery_images[]` with `{ path, url }`.

### JobListItem

| Field | Type |
|-------|------|
| slug | string |
| title | string |
| department | string |
| location | string |
| employment_type | string |
| short_description | string |
| posted_at | date string \| null |
| deadline_at | date string \| null |

### JobDetail

JobListItem plus `summary`, `description`, `qualifications`, `responsibilities`, `application_email`.

### QuedanItem

| Field | Type |
|-------|------|
| formatted_price | string |
| trading_date | date string |
| weekending_date | date string |
| difference | number \| null |
| trend | string |
| trend_class | string |
| trend_icon | string |
| price_subtext | string \| null |
| notes | string \| null |
