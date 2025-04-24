import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Router } from '@angular/router';

@Component({
  selector: 'app-payment',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './payment.component.html',
  styleUrls: ['./payment.component.scss']
})
export class PaymentComponent implements OnInit {
  showMessage = false;
  fading = false;

  constructor(private router: Router) {}

  ngOnInit(): void {
    // On simule la confirmation du paiement
    this.showSuccess();
  }

  private showSuccess() {
    this.showMessage = true;

    // Après 5 secondes on lance le fade‑out…
    setTimeout(() => {
      this.fading = true;

      // …puis on redirige 1s plus tard (le temps de l’animation)
      setTimeout(() => {
        this.router.navigate(['/search-ride']);
      }, 1000);
    }, 3000);
  }
}

