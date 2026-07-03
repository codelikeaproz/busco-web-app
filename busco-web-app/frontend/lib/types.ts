export type NewsListItem = {
  id: number;
  title: string;
  sub_title: string | null;
  excerpt: string;
  category: string;
  image_url: string;
  is_featured: boolean;
  created_at: string | null;
};

export type NewsDetail = NewsListItem & {
  content: string;
  gallery_images: Array<{ path: string; url: string }>;
};

export type JobListItem = {
  slug: string;
  title: string;
  department: string;
  location: string;
  employment_type: string;
  short_description: string;
  posted_at: string | null;
  deadline_at: string | null;
};

export type JobDetail = JobListItem & {
  summary: string | null;
  description: string | null;
  qualifications: string | null;
  responsibilities: string | null;
  application_email: string;
};

export type QuedanItem = {
  id: number;
  formatted_price: string;
  trading_date: string | null;
  weekending_date: string | null;
  difference: number | null;
  trend: string | null;
  trend_class: string;
  trend_icon: string;
  price_subtext: string | null;
  notes: string | null;
};

export type PaginationMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number | null;
  to: number | null;
};

export type PaginationLinks = {
  first: string | null;
  last: string | null;
  prev: string | null;
  next: string | null;
};

export type Paginated<T> = {
  data: T[];
  meta: PaginationMeta;
  links: PaginationLinks;
};

export type HomeResponse = {
  latest_news: NewsListItem[];
  active_quedan: QuedanItem | null;
  previous_quedan: QuedanItem | null;
};

export type QuedanResponse = {
  active_price: QuedanItem | null;
  previous_price: QuedanItem | null;
  history: Paginated<QuedanItem>;
};

export type CareersResponse = Paginated<JobListItem> & {
  departments: string[];
  employment_types: string[];
  filters: {
    department: string;
    employment_type: string;
    search: string;
  };
};
