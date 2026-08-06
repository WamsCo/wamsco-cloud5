@component('mail::message')
<img src="{{$body['logo']}}" style="width: 30%; display: block;margin: 0px auto;padding-bottom: 15px;" alt="Logo"/>
<h1><span style="color:rgb(2, 16, 120);">{{$body['entite']}}</span></h1><br>    
<h1>Bonjour: Mme/M. <span style="color:green;">{{$body['name']}}</span></h1><br> 
<p><span style="font-weight:700;"> Félicitations !</span>.</p>   
<p>Votre compte sur l'application <span style="font-weight:700;"> {{ config('app.name') }}</span> est maintenant opérationnel.</p>
<p>
<span>Nom utilisateur : <span style="color:green; padding: 3px 8px;">{{$body['name']}}</span><br>
<span>Votre email : <span style="color:rgb(197, 89, 1); padding: 3px 8px;">{{$body['email']}}</span><br>
<span>Date création : <span style="color:rgb(28, 28, 28); padding: 3px 8px;">{{$body['created_at']}}</span><br>
</p>

<p style="color:rgb(216, 9, 6);">Confirmez votre adresse email en cliquant sur ce lien.</p>
@component('mail::button', ['url' => $body['lien'], 'color' => 'success'])
Je confirme mon email !
@endcomponent

<h5 style="text-align: center;">Ignorez ce message si vous ne l'attendiez pas.</h5> 
    
 

@component('mail::table')
| Adesse                     | Heure d'ouverture                |
| :----------------          | :------------------------------  |
| Douala, Cameroun           | Lundi à vendredi : 8h00 - 18h00  | 
|                            | Samedi, : 8h00 - 18h00           | 
@endcomponent
Thanks,<br>
{{ config('app.name') }} Team.<br>
@endcomponent


