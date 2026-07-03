<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Candidats — {{ $jobOffer->titre }}</title>
    <style>
        @page {
            margin: 28px 32px;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1f2937;
            font-size: 11px;
            line-height: 1.4;
        }
        .header {
            border-bottom: 2px solid #1a6b3c;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header h1 {
            font-size: 17px;
            color: #124d2b;
            margin: 0 0 4px 0;
        }
        .header p {
            font-size: 10px;
            color: #6b7280;
            margin: 0;
        }
        .meta {
            width: 100%;
            margin-bottom: 14px;
        }
        .meta td {
            font-size: 10px;
            color: #4b5563;
            padding: 2px 0;
        }
        .meta td.label {
            font-weight: bold;
            color: #1f2937;
            width: 110px;
        }
        table.candidates {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        table.candidates thead th {
            background: #124d2b;
            color: #ffffff;
            font-size: 10px;
            text-align: left;
            padding: 7px 8px;
            font-weight: bold;
        }
        table.candidates tbody td {
            font-size: 10px;
            padding: 7px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        table.candidates tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 9px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-recue { background: #f3f4f6; color: #4b5563; }
        .badge-en_discussion { background: #dbeafe; color: #1d4ed8; }
        .badge-acceptee { background: #dcfce7; color: #15803d; }
        .badge-refusee { background: #fee2e2; color: #b91c1c; }
        .col-num { width: 24px; }
        .col-doc { width: 90px; }
        .col-date { width: 60px; }
        .footer {
            position: fixed;
            bottom: -16px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
        }
        .empty {
            text-align: center;
            color: #9ca3af;
            padding: 30px 0;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Liste des candidats — {{ $jobOffer->titre }}</h1>
        <p>{{ $jobOffer->institution->nom }} · {{ $jobOffer->lieu }} · Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <table class="meta">
        <tr>
            <td class="label">Type de contrat</td>
            <td>{{ $jobOffer->typeContratLabel() }}</td>
            <td class="label">Métier / Profession</td>
            <td>{{ $jobOffer->metierLabel() }}</td>
        </tr>
        <tr>
            <td class="label">Nombre de candidats</td>
            <td>{{ $candidatures->count() }}</td>
            <td class="label">Expire le</td>
            <td>{{ $jobOffer->date_expiration->format('d/m/Y') }}</td>
        </tr>
    </table>

    @if($candidatures->isEmpty())
        <p class="empty">Aucun candidat pour le moment.</p>
    @else
        <table class="candidates">
            <thead>
                <tr>
                    <th class="col-num">#</th>
                    <th>Candidat</th>
                    <th>Email</th>
                    <th class="col-date">Date</th>
                    <th>Statut</th>
                    <th class="col-doc">Documents</th>
                    <th>Note / Motivation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidatures as $i => $candidature)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $candidature->user->name }}</td>
                        <td>{{ $candidature->user->email }}</td>
                        <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $candidature->statut_candidature }}">
                                {{ $candidature->statutLabel() }}
                            </span>
                        </td>
                        <td>
                            @if($candidature->aCv()) CV @endif
                            @if($candidature->aCv() && $candidature->aLettreMotivation()) + @endif
                            @if($candidature->aLettreMotivation()) Lettre @endif
                            @if(! $candidature->aDocuments()) — @endif
                        </td>
                        <td>{{ Str::limit($candidature->note_motivation, 90) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        InfoJob-Togo · Liste des candidats — {{ $jobOffer->titre }}
    </div>

</body>
</html>
