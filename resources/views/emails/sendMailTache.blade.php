@component('mail::message')
<img src="{{$body['logo']}}" style="width: 30%; display: block;margin: 0px auto;padding-bottom: 15px;" alt="Logo"/>
<h1><span style="color:rgb(2, 16, 120);">{{$body['entite']}}</span></h1><br>    
<h1>Bonjour: Mme/M. <span style="color:green;">{{$body['name']}}</span></h1><br> 
<p>Ce message automatique vous informe qu'une nouvelle tâche vous a été confiée via l'application <span style="font-weight:700;"> {{ config('app.name') }}</span>.</p>   
<p>Veuillez prendre connaissance des détails de la tâche et mettre à jour son statut dès que possible. </p>
<p>
<span>Tâche N°: <span style="color:green; padding: 3px 8px;">{{$body['reference']}}</span><br>
<span>Statut : <span style="color:rgb(225, 3, 3); padding: 3px 8px;">{{$body['etape']}}</span><br>
<span>Priorité : <span style="color:green; padding: 3px 8px;">{{$body['priorite']}}</span><br>
<span>Date clôture : <span style="color:rgb(197, 89, 1); padding: 3px 8px;">{{$body['date_cloture']}}</span><br>
<span>Temps alloué (H:min) : <span style="color:rgb(29, 47, 29); padding: 3px 8px;">{{$body['temps_alloue']}} min</span><br>
<span>Date création : <span style="color:rgb(28, 28, 28); padding: 3px 8px;">{{$body['created_at']}}</span><br>
<span>Message : <br> <span style="color:rgb(9, 29, 88); padding: 3px 8px;">{{$body['nom_tache']}}</span>
</p>

<p style="color:rgb(216, 9, 6);">NB: Vous pouvez consulter la tâche en cliquant sur ce lien.</p>
@component('mail::button', ['url' => $body['lien'], 'color' => 'success'])
Voir la tâche
@endcomponent

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


