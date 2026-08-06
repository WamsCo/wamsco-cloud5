@component('mail::message')
<img src="{{$body['logo']}}" style="width: 30%; display: block;margin: 0px auto;padding-bottom: 15px;" alt="Logo"/>
<h1><span style="color:rgb(2, 16, 120);">{{$body['entite']}}</span></h1><br>    
<h1>Bonjour: Mme/M. <span style="color:green;">{{$body['name']}}</span></h1><br> 
<p>Ce message automatique vous informe des détails concernant votre <span style="font-weight:700;">avance ou prêt</span> via l'application <span style="font-weight:700;"> {{ config('app.name') }}</span>.</p>   
<p>Veuillez prendre connaissance des détails ci-dessous: </p>
<p>
<span>Statut avance ou prêt : <span style="color:rgb(216, 9, 6); padding: 3px 8px;">{{$body['statut']}}</span><br>
<span>Avance N°: <span style="color:green; padding: 3px 8px;">AV-{{$body['id_avance']}}</span><br>
<span>Type de prêt : <span style="color:rgb(225, 3, 3); padding: 3px 8px;">{{$body['type_pret']}}</span><br>
<span>Libelle : <span style="color:green; padding: 3px 8px;">{{$body['libelle']}}</span><br>
<span>Montant : <span style="color:rgb(175, 26, 3); padding: 3px 8px;">{{$body['montant']}} {{$body['devise']}}</span><br>
<span>Nbre tranche : <span style="color:rgb(29, 47, 29); padding: 3px 8px;">{{$body['nombre_tranche']}}</span><br>
<span>Mode règlement : <span style="color:rgb(29, 47, 29); padding: 3px 8px;">{{$body['mode_reglement']}}</span><br>
<span>Date paiement : <span style="color:rgb(197, 89, 1); padding: 3px 8px;">{{$body['date_paiement']}}</span><br>
<span>Déja prélève : <span style="color:rgb(6, 143, 6); padding: 3px 8px;">{{$body['montant_deja_preleve']}} {{$body['devise']}}</span><br>
<span>Date création : <span style="color:rgb(28, 28, 28); padding: 3px 8px;">{{$body['created_at']}}</span><br>
<span>Note : <br> <span style="color:rgb(9, 29, 88); padding: 3px 8px;">{{$body['note']}}</span>
</p>

{{-- <p style="color:rgb(216, 9, 6);">NB: Vous pouvez consulter la tâche en cliquant sur ce lien.</p> --}}
{{-- @component('mail::button', ['url' => $body['lien'], 'color' => 'success'])
Voir la tâche
@endcomponent --}}

<h5>Visitez notre site web pour en savoir plus sur nos services via le bouton ci-dessous.</h5> 
@component('mail::button', ['url' => $body['url_a']])
   WamsCo Cloud
@endcomponent
    
 

@component('mail::table')
| Adesse                     | Heure d'ouverture                |
| :----------------          | :------------------------------  |
| Douala, Cameroun           | Lundi à vendredi : 8h00 - 18h00  | 
|                            | Samedi, : 8h00 - 18h00           | 
@endcomponent
Thanks,<br>
{{ config('app.name') }} Team.<br>
@endcomponent


