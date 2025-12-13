<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Facture {{ $invoice->number }}</title>

  <style>
    /* Marges PDF (plus stable que body padding) */
    @page { margin: 22mm 18mm; }

    * { box-sizing: border-box; }

    body {
      font-family: DejaVu Sans, Arial, system-ui, -apple-system, sans-serif;
      color: #0f172a;
      background: #ffffff;
      margin: 0;
      padding: 0;
      font-size: 12.5px;
      line-height: 1.45;
    }

    .card {
      width: 100%;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      overflow: hidden; /* utile pour bandeau */
    }

    /* Bandeau header */
    .header {
      background: #f8fafc;
      border-bottom: 1px solid #e5e7eb;
      padding: 16px 18px;
    }

    .header-table {
      width: 100%;
      border-collapse: collapse;
    }
    .header-table td { vertical-align: top; }

    .brand {
      font-weight: 800;
      letter-spacing: 0.10em;
      font-size: 16px;
      color: #0b1220;
      margin-bottom: 6px;
    }

    .badges { margin-top: 6px; }

    .pill {
      display: inline-block;
      padding: 6px 10px;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 700;
      border: 1px solid #e5e7eb;
      background: #ffffff;
      color: #0f172a;
    }

    .pill-primary {
      border-color: #dbeafe;
      background: #eff6ff;
      color: #1d4ed8;
    }

    /* (Optionnel) badge statut sans changer le texte */
    .pill-status {
      margin-left: 8px;
      border-color: #e5e7eb;
      background: #f1f5f9;
      color: #334155;
    }

    .meta {
      text-align: right;
      font-size: 12px;
      color: #475569;
    }
    .meta strong {
      display: block;
      font-size: 13px;
      color: #0f172a;
      margin-bottom: 2px;
    }

    .content {
      padding: 16px 18px 18px;
    }

    .grid-table {
      width: 100%;
      border-collapse: collapse;
    }
    .grid-table td {
      vertical-align: top;
      padding: 10px 0;
    }

    .section-title {
      font-size: 12px;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      font-weight: 800;
      color: #0f172a;
      margin: 0 0 6px;
    }

    .muted { color: #64748b; }

    .block {
      padding: 12px 12px;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      background: #ffffff;
    }

    .kv {
      width: 100%;
      border-collapse: collapse;
    }
    .kv td {
      padding: 2px 0;
      font-size: 12.5px;
    }
    .kv .k { color: #64748b; width: 46%; }
    .kv .v { color: #0f172a; font-weight: 600; }

    .spacer { height: 14px; }

    .items {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      overflow: hidden;
    }

    .items th, .items td {
      padding: 10px 10px;
      border-bottom: 1px solid #e5e7eb;
      vertical-align: top;
    }

    .items thead th {
      background: #f8fafc;
      color: #0f172a;
      font-size: 12px;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      font-weight: 800;
    }

    .items td.amount, .items th.amount { text-align: right; white-space: nowrap; }

    .items tbody tr:last-child td { border-bottom: 0; }

    /* Total */
    .total-wrap {
      margin-top: 10px;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      background: #f8fafc;
      padding: 12px 12px;
      page-break-inside: avoid;
    }

    .total-table {
      width: 100%;
      border-collapse: collapse;
    }

    .total-table td {
      padding: 2px 0;
      font-size: 13px;
    }

    .total-table .label {
      font-weight: 800;
      letter-spacing: 0.02em;
    }

    .total-table .value {
      text-align: right;
      font-weight: 900;
      font-size: 15px;
      white-space: nowrap;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <table class="header-table">
        <tr>
          <td>
            <div class="brand">OMEGA EDITION</div>
            <div class="badges">
              <span class="pill pill-primary">FACTURE</span>
              <span class="pill pill-status">{{ strtoupper($invoice->status) }}</span>
            </div>
          </td>
          <td class="meta">
            <strong>N° {{ $invoice->number }}</strong>
            <div class="muted">—</div>
          </td>
        </tr>
      </table>
    </div>

    <div class="content">
      <table class="grid-table">
        <tr>
          <td style="padding-right: 10px;">
            <div class="section-title">Facturé à</div>
            <div class="block">
              <div style="font-weight: 800; font-size: 13px;">{{ $user?->name }}</div>
              <div class="muted">{{ $user?->email }}</div>
            </div>
          </td>

          <td style="padding-left: 10px;">
            <div class="section-title">Détails</div>
            <div class="block">
              <table class="kv">
                <tr>
                  <td class="k">Période</td>
                  <td class="v">{{ $invoice->period_label ?? 'Mensuel' }}</td>
                </tr>
                <tr>
                  <td class="k">Statut</td>
                  <td class="v">{{ strtoupper($invoice->status) }}</td>
                </tr>
                <tr>
                  <td class="k">Date de paiement</td>
                  <td class="v">{{ optional($invoice->paid_at)->format('d/m/Y') ?? '—' }}</td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
      </table>

      <div class="spacer"></div>

      <div class="section-title">Détail</div>
      <table class="items">
        <thead>
          <tr>
            <th>Description</th>
            <th class="amount">Montant</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Abonnement {{ $invoice->period_label ?? 'Mensuel' }}</td>
            <td class="amount">{{ number_format($invoice->amount_xaf, 0, '.', ' ') }} XAF</td>
          </tr>
        </tbody>
      </table>

      <div class="total-wrap">
        <table class="total-table">
          <tr>
            <td class="label">Total</td>
            <td class="value">{{ number_format($invoice->amount_xaf, 0, '.', ' ') }} XAF</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
