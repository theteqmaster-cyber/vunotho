# VUNOTHO — Farmer-to-Market Operating System

An integrated digital operating system and circular value-recovery platform for smallholder agriculture, built for the Enactus International Competition blueprint.

---

## 🌟 Key Features

1. **Price Intelligence & Net-Return Engine**: Real-time transparency showing $\text{Gross Price} - \text{Transport} - \text{Platform Fee} = \mathbf{\text{Net Return}}$ before accepting orders.
2. **Logistics & Multi-Farmer Aggregation**: Weight-and-distance dynamic logistics pooling smallholder harvest lots into consolidated transporter routes.
3. **Circular Value-Recovery Module**: 4-tier non-binary produce grading (Fresh Market $\rightarrow$ Value-Added Processing $\rightarrow$ Livestock Feed $\rightarrow$ Organic Compost) tracking kilograms diverted from waste.
4. **Realistic Sandbox Settlement**: Mobile Money (EcoCash PIN authorization) and Cash on Collection generating verifiable digital receipts.
5. **Enactus Impact Scorecard**: Live dashboard calculating farmer net income lift, waste diversion, logistics savings, and youth jobs created.
6. **Offline-First Resilience**: Full offline operation via IndexedDB with automatic background synchronization to the PHP database when connectivity returns.

---

## 🛠️ Technology Stack & Architecture

- **Backend**: PHP REST API (`api/`) with PDO database abstraction supporting **PostgreSQL**, **MySQL**, and **SQLite**.
- **Frontend**: Vanilla JavaScript (ES6+), HTML5, CSS3 with strict 70/20/10 visual identity (*Deep Navy, White, Gold, Emerald Green, Alert Orange, Connectivity Teal*).
- **Deployment**: Serverless on **Vercel** (`vercel.json`) with `vercel-php@0.7.2`.

---

## 🚀 Running Locally

You can run the PHP built-in web server locally:

```bash
# Start local PHP server on port 8000
php -S localhost:8000
```

Open your browser and navigate to: `http://localhost:8000`

---

## ☁️ Deploying to Vercel

1. **Install Vercel CLI** (or import the repository directly on [vercel.com](https://vercel.com)):
   ```bash
   npm i -g vercel
   vercel
   ```
2. **Database Configuration** (Optional for Remote DB):
   In your Vercel Project Settings $\rightarrow$ **Environment Variables**, add:
   - `DATABASE_URL`: Your PostgreSQL or MySQL connection string (e.g. from Neon, Supabase, PlanetScale, or Aiven).
   - *(If no `DATABASE_URL` is provided, Vercel will default to internal temporary SQLite).*

---

## 📚 System Analysis & Documentation

- Open **[`vunotho_system_analysis.html`](file:///home/mphatic/Desktop/vunotho/vunotho_system_analysis.html)** to explore the interactive SADM diagrams (DFD Level 0/1, ERD, Sequence, and State Machine).
