@component('mail::message')
<img src="{{$body['logo']}}" style="width: 30%; display: block;margin: 0px auto;padding-bottom: 15px;" alt="Logo"/>
<h1><span style="color:rgb(176, 120, 0);">{{$body['entite']}}</span></h1><br>    
<h1>FACTURE À L'INTENTION DE: M./Mme <span style="color:green;">{{$body['name']}}</span></h1><br>    
<p>Ce qui suit est un courriel qui vous a été envoyé depuis l'application « {{ config('app.name') }} ».</p>
<p>Le message a été envoyé à M./Mme <span style="color:green;">{{$body['name']}}</span> qui a effectué(e) les achats suivants : </p>
<h3>Facture N°: {{$body['objet']}}</h3>
<p>
   Date vente: <span style="font-weight: 500">{{date('d/m/Y H:i:s', strtotime($body['date_achat']))}}</span><br>
   Servi Par: <span style="font-weight: 600">{{$body['responsable']}}</span><br>
   Montant HT:  <span style="font-weight: 600">{{number_format($body['montant_remiser'],0,' ',' ')}} {{$body['devise']}}</span><br> 
   TVA:  <span style="font-weight: 600">{{number_format($body['tva'],0,' ',' ')}} {{$body['devise']}}</span><br>    
   Précompte:  <span style="font-weight: 600">{{number_format($body['precompte'],0,' ',' ')}} {{$body['devise']}}</span><br>    
   Montant TTC:  <span style="font-weight: 600; color:rgb(179, 0, 0);">{{number_format($body['montantTTC'],0,' ',' ')}} {{$body['devise']}}</span><br> 
   Paiement:  <span style="font-weight: 600">{{$body['mode_paiement']}} @if($body['mode_paiement2'] !='') & {{$body['mode_paiement2']}} @endif</span><br>   
</p>
@component('mail::table')
    | Désignation    | Prix       | Qté     | Remise     | Total    | Tva       |Précompte   |
    | :------------- |-----------:|--------:|------------:|---------:|----------:|----------:| {{-- ceci permet donner le sens de la phrase (Debut :--, Centrer :--:, fin --:) --}}
    @foreach($detail as $details)
    | {{$details->designation}}   | {{$details->prix_vente}} | {{$details->quantite}}| {{$details->remise_pourcent}}% |{{$details->montant_total_remiser}} |{{$details->tva}}% |{{$details->precompte}}% | 
    @endforeach
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@endcomponent
     
<h4>Visitez notre site web pour en savoir plus sur nos services via le bouton ci-dessous.</h4> 
@component('mail::button', ['url' => $body['url_a']])
   WamsCo Cloud
@endcomponent
    
 

@component('mail::table')
| Adesse                     | Heure d'ouverture                |
| :----------------          | :------------------------------  |
| Douala, Cameroun          | Lundi à vendredi : 8h00 - 18h00  | 
|                            | Samedi, : 8h00 - 18h00           | 
@endcomponent
Thanks,<br>
{{ config('app.name') }} Team.<br>
@endcomponent

