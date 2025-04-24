import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-profile',
  standalone: true,
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss'],
  imports: [CommonModule],
})
export class ProfileComponent {
  // Onglet actif : 'apropos' ou 'compte'
  activeTab: string = 'apropos';

  // Informations utilisateur (à récupérer dynamiquement via un service dans une vraie app)
  userAvatarUrl: string = 'assets/images/Alice_Durand.png';
  userName: string = 'Alice Durand';
  userEmail: string = 'alice.durand@example.com';
  userPhone: string = '01 23 45 67 89';
  userStatus: string = 'Conductrice';
  userRating: number = 4.5;
  userVehicles = { 
  brand: 'Renault',
  model: 'Clio 5',
  licensePlate: 'ZX-009-RR'
};

  // Exemple d'array pour les trajets publiés (si nécessaire)
  userRides = [
    { depart: 'Paris', arrival: 'Lyon' },
    { depart: 'Marseille', arrival: 'Nice' }
  ];

  constructor(private router: Router) {}

  // Méthode pour changer d'onglet
  showTab(tab: string) {
    this.activeTab = tab;
  }

  // Actions pour l'onglet "À propos de vous"
  verifyIdentity() {
    console.log('Vérification de l’identité');
    // Implémenter la logique de vérification d’identité
  }

  verifyPhone() {
    console.log('Vérification du numéro de téléphone');
    // Implémenter la logique de vérification du téléphone
  }

  editPhoto() {
    console.log('Modification de la photo de profil');
    // Par exemple, ouvrir un modal pour changer la photo
  }

  editPersonalInfos() {
    console.log('Modification des informations personnelles');
    // Rediriger vers une page de modification ou ouvrir un modal
  }

  addVehicle() {
    console.log('Ajout d’un véhicule');
    // Implémenter la logique pour ajouter un véhicule
  }

  // Actions pour l'onglet "Compte"
  changePassword() {
    console.log('Modification du mot de passe');
    // Implémenter la logique de changement de mot de passe
  }

  editAddress() {
    console.log('Modification de l’adresse postale');
    // Implémenter la logique d’édition d’adresse
  }

  editPaymentMethods() {
    console.log('Modification des moyens de paiement');
    // Implémenter la logique d’édition des méthodes de paiement
  }

  refundSettings() {
    console.log('Configuration des virements et remboursements');
    // Implémenter la logique de configuration pour les virements
  }

  onLogout() {
    console.log('Déconnexion');
    // Implémente ici la logique de logout (via un AuthService, par exemple)
    // Puis redirige l'utilisateur vers la page de connexion
    this.router.navigate(['/login']);
  }
}