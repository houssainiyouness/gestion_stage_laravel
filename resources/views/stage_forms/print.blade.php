<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Formulaires stage</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 20px;
            background: #f3f4f6;
        }

        .toolbar {
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .page {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 20px;
            padding: 18mm;
            box-sizing: border-box;
            page-break-after: always;
        }

        h1 {
            text-align: center;
            font-size: 20px;
            text-transform: uppercase;
        }

        h2 {
            font-size: 16px;
            border-bottom: 1px solid #111;
            padding-bottom: 5px;
            margin-top: 22px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        th, td {
            border: 1px solid #111;
            padding: 7px;
            font-size: 13px;
            text-align: left;
            vertical-align: top;
        }

        th {
            width: 30%;
            background: #f3f4f6;
        }

        .signatures {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 50px;
            text-align: center;
            font-size: 13px;
        }

        .signature-box {
            height: 90px;
            border-top: 1px solid #111;
            padding-top: 8px;
        }

        @media print {
            body {
                margin: 0;
                background: white;
            }

            .toolbar {
                display: none;
            }

            .page {
                width: auto;
                min-height: auto;
                margin: 0;
                border: none;
                page-break-after: always;
            }
        }
    </style>
</head>

<body>

<div class="toolbar">
    <button class="btn" onclick="window.print()">Imprimer</button>
    <a class="btn" href="{{ route('internships.show', $internship) }}">Retour</a>
</div>

<section class="page">
    <h1>Formulaire de demande de stage</h1>

    <h2>Identité</h2>
    <table>
        <tr>
            <th>Nom</th>
            <td>{{ $stageForm->nom }}</td>
        </tr>
        <tr>
            <th>Prénom</th>
            <td>{{ $stageForm->prenom }}</td>
        </tr>
        <tr>
            <th>Date de naissance</th>
            <td>{{ optional($stageForm->date_naissance)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Lieu de naissance</th>
            <td>{{ $stageForm->lieu_naissance }}</td>
        </tr>
        <tr>
            <th>Nationalité</th>
            <td>{{ $stageForm->nationalite }}</td>
        </tr>
        <tr>
            <th>CIN / Passeport</th>
            <td>{{ $stageForm->cin }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $stageForm->email }}</td>
        </tr>
        <tr>
            <th>Téléphone</th>
            <td>{{ $stageForm->telephone_portable }}</td>
        </tr>
        <tr>
            <th>Adresse</th>
            <td>{{ $stageForm->adresse }}</td>
        </tr>
    </table>

    <h2>Établissement d’origine</h2>
    <table>
        <tr>
            <th>Nom de l’établissement</th>
            <td>{{ $stageForm->etablissement_nom }}</td>
        </tr>
        <tr>
            <th>Adresse</th>
            <td>{{ $stageForm->etablissement_adresse }}</td>
        </tr>
        <tr>
            <th>Téléphone</th>
            <td>{{ $stageForm->etablissement_telephone }}</td>
        </tr>
        <tr>
            <th>Fax</th>
            <td>{{ $stageForm->etablissement_fax }}</td>
        </tr>
    </table>

    <h2>Formation</h2>
    <table>
        <tr>
            <th>Spécialité</th>
            <td>{{ $stageForm->specialite }}</td>
        </tr>
        <tr>
            <th>Niveau d’étude</th>
            <td>{{ $stageForm->niveau_etude }}</td>
        </tr>
        <tr>
            <th>Filière</th>
            <td>{{ $stageForm->filiere }}</td>
        </tr>
    </table>

    <h2>Stage</h2>
    <table>
        <tr>
            <th>Type</th>
            <td>{{ $stageForm->stage_type === 'nouveau' ? 'Nouveau stage' : 'Renouvellement de stage' }}</td>
        </tr>
        <tr>
            <th>Période</th>
            <td>
                Du {{ optional($internship->start_date)->format('d/m/Y') }}
                au {{ optional($internship->end_date)->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <th>Jours de stage</th>
            <td>{{ $stageForm->jours_stage }}</td>
        </tr>
        <tr>
            <th>Horaires</th>
            <td>De {{ $stageForm->horaire_debut }} à {{ $stageForm->horaire_fin }}</td>
        </tr>
        <tr>
            <th>Laboratoire d’accueil</th>
            <td>{{ $stageForm->laboratoire_accueil }}</td>
        </tr>
        <tr>
            <th>Maître de stage</th>
            <td>{{ $stageForm->maitre_stage }}</td>
        </tr>
        <tr>
            <th>Sujet de stage</th>
            <td>{{ $stageForm->sujet_stage }}</td>
        </tr>
    </table>

    <div class="signatures">
        <div class="signature-box">Signature du demandeur</div>
        <div class="signature-box">Avis du maître de stage</div>
        <div class="signature-box">Vice Doyen / Administration</div>
    </div>
</section>

<section class="page">
    <h1>Fiche de renseignement du stagiaire</h1>

    <h2>Informations personnelles</h2>
    <table>
        <tr>
            <th>Nom et prénom</th>
            <td>{{ trim($stageForm->nom . ' ' . $stageForm->prenom) }}</td>
        </tr>
        <tr>
            <th>Date de naissance</th>
            <td>{{ optional($stageForm->date_naissance)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>CIN</th>
            <td>{{ $stageForm->cin }}</td>
        </tr>
        <tr>
            <th>Téléphone</th>
            <td>{{ $stageForm->telephone_portable }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $stageForm->email }}</td>
        </tr>
        <tr>
            <th>Adresse</th>
            <td>{{ $stageForm->adresse }}</td>
        </tr>
    </table>

    <h2>Information</h2>
    <table>
        <tr>
            <th>Établissement</th>
            <td>{{ $stageForm->etablissement_nom }}</td>
        </tr>
        <tr>
            <th>Niveau</th>
            <td>{{ $stageForm->niveau_etude }}</td>
        </tr>
        <tr>
            <th>Spécialité</th>
            <td>{{ $stageForm->specialite }}</td>
        </tr>
    </table>

    <h2>Affectation du stagiaire</h2>
    <table>
        <tr>
            <th>Service d’affectation</th>
            <td>{{ $stageForm->service_affectation }}</td>
        </tr>
        <tr>
            <th>Encadrant(s)</th>
            <td>{{ $stageForm->encadrants }}</td>
        </tr>
        <tr>
            <th>Période de stage</th>
            <td>
                Du {{ optional($internship->start_date)->format('d/m/Y') }}
                au {{ optional($internship->end_date)->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <th>Sujet de stage</th>
            <td>{{ $stageForm->sujet_stage }}</td>
        </tr>
    </table>

    <h2>Pièces obligatoires</h2>
    <table>
        <tr>
            <th>Attestation d’assurance</th>
            <td>{{ $stageForm->assurance_ok ? 'Confirmée par l’étudiant' : 'À fournir' }}</td>
        </tr>
        <tr>
            <th>Convention de stage</th>
            <td>{{ $stageForm->convention_ok ? 'Confirmée par l’étudiant' : 'À fournir' }}</td>
        </tr>
    </table>

    <div class="signatures">
        <div class="signature-box">Signature RH</div>
        <div class="signature-box">Signature chef de service</div>
        <div class="signature-box">Signature administration</div>
    </div>
</section>

<section class="page">
    <h1>Engagement</h1>

    <p>
        Je soussigné(e),
        <strong>{{ trim($stageForm->nom . ' ' . $stageForm->prenom) }}</strong>,
        m’engage à respecter le règlement de stage, les règles internes de l’établissement
        et la confidentialité des données mises à ma disposition.
    </p>

    <h2>Engagements</h2>

    <ol>
        <li>Respecter le règlement de stage.</li>
        <li>Respecter les règlements intérieurs en vigueur.</li>
        <li>Respecter la confidentialité des données.</li>
        <li>Signaler toute défaillance ou dysfonctionnement au maître de stage.</li>
        <li>Restituer tout matériel ou badge à la fin du stage.</li>
    </ol>

    <table>
        <tr>
            <th>Date de début</th>
            <td>{{ optional($internship->start_date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Date de fin</th>
            <td>{{ optional($internship->end_date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Engagement confirmé dans la plateforme</th>
            <td>{{ $stageForm->engagement_ok ? 'Oui' : 'Non' }}</td>
        </tr>
    </table>

    <div class="signatures">
        <div class="signature-box">Signature du stagiaire</div>
        <div class="signature-box">Signature maître de stage</div>
        <div class="signature-box">Signature administration</div>
    </div>
</section>

</body>
</html>