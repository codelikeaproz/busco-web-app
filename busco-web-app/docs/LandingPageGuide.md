LANDING PAGE GUIDE
BUSCO Sugar Milling Co., Inc. Website
1. Purpose of This Guide

This document defines the UI/UX structure, visual identity, and frontend page composition of the BUSCO Sugar Milling Co., Inc. Website.

It aligns with the overall system architecture and three-layer structure defined in the System Architecture documentation.

This guide focuses on:

Visual consistency

Layout standards

User flow

Page structure

Component styling

Frontend-to-backend alignment

2. Design System & Brand Identity
2.1 Official Color Palette
Element	Color Code	Usage
Primary Green	#1B5E20	Navigation, headings, overlays
Accent Green	#2E7D32	Icons, hover states
Highlight Yellow	#F9A825	CTAs, badges, featured labels
White	#FFFFFF	Main background
Off White	#F7F9F5	Alternate section background
2.2 Typography Guidelines

Primary Headings (H1, H2): #1B5E20

Subheadings (H3, H4): #2E7D32

Buttons & CTAs: #F9A825

Body Text: Dark Gray / Black

Hero text: White on green overlay

2.3 Layout Rules

Max width container: 1200px

Section padding: 80px top & bottom

Use alternating backgrounds (#FFFFFF / #F7F9F5)

Card components for structured content

Consistent spacing grid

3. Website Page Structure (Frontend Implementation)
3.1 Home Page
Objective:

Provide strong first impression and highlight core information.

Sections:

Hero Section

Background image (Sugar mill photo)

Green overlay (#1B5E20)

Bold slogan

CTA Button (#F9A825)

Quick Navigation Links

About

Services

Process

Careers

Contact

Latest News Preview

Pulled dynamically from News Module

Show 3 recent posts

Thumbnail + Title + Date

Active Quedan Price Highlight

Large price display

Trend indicator (UP / DOWN / NO CHANGE)

Difference display

Community Highlight

Call to Action Section

“Learn More”

“View Careers”

“Contact Us”

3.2 About Page
Content:

Company Overview

History (Founded in Butong, Quezon, Bukidnon)

Role in Sugar Industry

Production Capacity

Timeline (Foundation → Growth → Present)

Vision & Mission Summary

Layout:

Text + Image Split Layout

Timeline component

Alternating section background

3.3 Services / Operations
Sections:

Sugar Milling

Cane Procurement

Quality Control

Supply Distribution

Farmer Support

Design:

Icon + Title + Description cards

3-column responsive grid

Hover accent color: #2E7D32

3.4 Sugar Milling Process Page
Objective:

Educate visitors about sugar production.

Step Cards (Sequential Layout):

Cane Delivery & Weighing

Cane Cutting & Shredding

Juice Extraction

Clarification

Evaporation

Crystallization

Centrifugation

Drying & Bagging

Design:

Vertical step layout

Alternating background sections

Green icons (#2E7D32)

Process number badges

3.5 News & Achievements
Public View:

News list (Card layout)

Title

Thumbnail

Date

Category

Featured badge (#F9A825)

Click → Opens Full Article Page

Backend Integration:

Connected to:

news table

Status (Published/Draft)

Image upload

CRUD module (Admin only)

3.6 Quedan Price Page
Displays:

Week Label

Current Price

Effective Date

Trend

Price Difference

Historical Table

Visual Indicators:

Increase → Green badge

Decrease → Red badge

No Change → Neutral

3.7 Corporate Mission & Vision
Sections:

Vision

Mission

Core Values:

Safety

Quality

Community

Growth

Design:

Icon + Text layout

Quote-style block

Highlight accents in #F9A825

3.8 Community & Training
Content:

CSR Programs

Farmer Trainings

Workshops

Outreach Activities

Layout:

Card grid

Date indicators

Image thumbnails

CTA: “Learn More”

3.9 Careers Page
Sections:

Current Open Positions

Internship / OJT

Work Environment

Employee Benefits

Layout:

Job listing cards

Apply button (email link)

Tag labels for job type

3.10 Contact Page
Includes:

Company Address:
BUSCO, Butong, Quezon, Bukidnon

Email

Phone

Contact Form:

Name

Email

Message

Optional:

Embedded map

4. User Flow (Frontend Experience)

Visitor lands on Home

Views Quedan Price

Clicks About or Services

Reads Process Page

Visits News

Checks Careers

Submits inquiry through Contact

5. Component Reusability (Blade Structure Alignment)

Aligned with:

resources/views/

layouts/

app.blade.php

partials/

navbar.blade.php

footer.blade.php

components/

hero.blade.php

card.blade.php

price-highlight.blade.php

pages/

home.blade.php

about.blade.php

services.blade.php

process.blade.php

news.blade.php

announcement.blade.php

community.blade.php

careers.blade.php

contact.blade.php

6. Responsiveness Standards

Mobile-first layout

Grid stacking below 768px

Full-width buttons on mobile

Navigation collapses to hamburger menu

7. Accessibility & UX Considerations

Sufficient color contrast

Alt text for images

Keyboard navigation

Clear CTA labels

Readable font sizes

8. UI Consistency Rules

Use primary green for authority elements

Use yellow only for actions and highlights

Avoid mixing random colors

Maintain spacing consistency

Keep card design uniform

9. Alignment With System Architecture

This Landing Page Guide supports:

Presentation Layer (Frontend UI)

Laravel Blade templating

Database-driven dynamic content

Admin-controlled modules

Quedan price logic integration

It directly complements the system structure defined in the SYSTEM_ARCHITECTURE.md document 

SYSTEM_ARCHITECTURE

.

10. Final Deliverables

✔ Fully designed Home Page
✔ Structured Static Pages
✔ Integrated News Module
✔ Integrated Quedan Module
✔ Careers Listing Page
✔ Contact Form
✔ Responsive UI
✔ Brand-consistent Design
