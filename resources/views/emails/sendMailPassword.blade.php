@component('mail::message')
<img src="{{$body['logo']}}" style="width: 30%; display: block;margin: 0px auto;padding-bottom: 15px;" alt="Logo"/>
<h1><span style="color:rgb(2, 16, 120);">{{$body['entite']}}</span></h1><br>    
<h1>Bonjour: M./Mme <span style="color:green;">{{$body['name']}}</span></h1><br>    
<p>Ce qui suit est un courriel qui vous a été envoyé depuis l'application <span style="font-weight:700;"> {{ config('app.name') }}</span>.</p>
<p>Vous avez demandé à changer votre mot de passe. </p>
<h3>Votre nouveau mot de passe: <span style="background:green; color:rgb(255, 255, 255); padding: 3px 8px;">{{$body['password']}}</span></h3><br>

<p style="color:rgb(216, 9, 6);">NB: Connectez-vous et changez votre mot de passe aussitôt.</p>

<h4>Visitez notre site web pour en savoir plus sur nos services via le bouton ci-dessous.</h4> 
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


