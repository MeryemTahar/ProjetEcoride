import { Component, OnInit, AfterViewInit, ViewChild, ElementRef, Inject, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgFor, NgIf } from '@angular/common';
import { Chart, registerables } from 'chart.js';
import { AdminService } from '../../services/admin.service';

Chart.register(...registerables);

@Component({
  selector: 'app-admin',
  standalone: true,
  imports: [CommonModule, NgFor, NgIf],
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.scss']
})
export class AdminComponent implements OnInit, AfterViewInit {
  @ViewChild('ridesCanvas') ridesCanvas!: ElementRef<HTMLCanvasElement>;
  @ViewChild('creditsCanvas') creditsCanvas!: ElementRef<HTMLCanvasElement>;

  totalCredits = 0;
  accounts: { id: number; role: string; pseudo: string; isSuspended: boolean }[] = [];

  private rideChart!: Chart<'line'>;
  private creditChart!: Chart<'bar'>;

  constructor(
    private adminService: AdminService,
    @Inject(PLATFORM_ID) private platformId: Object
  ) {}

  ngAfterViewInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.rideChart   = new Chart(this.ridesCanvas.nativeElement, this.emptyLineCfg());
      this.creditChart = new Chart(this.creditsCanvas.nativeElement, this.emptyBarCfg());
    }
  }

  private emptyLineCfg(): Chart<'line'>['config'] {
    return {
      type: 'line',
      data: {
        labels: [],
        datasets: [{ label: 'Trajets / jour', data: [] }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true, title: { display: true, text: 'Nombre de trajets' } },
          x: { title: { display: true, text: 'Date' } }
        }
      }
    };
  }
  
  /** configuration d’un bar chart « vide » (placeholder) */
  private emptyBarCfg(): Chart<'bar'>['config'] {
    return {
      type: 'bar',
      data: {
        labels: [],
        datasets: [{ label: 'Crédits gagnés', data: [] }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true, title: { display: true, text: 'Crédits' } },
          x: { title: { display: true, text: 'Date' } }
        }
      }
    };
  }

  ngOnInit(): void {
    this.adminService.fetchAccounts().subscribe(list => (this.accounts = list));
  }

  /** suspendre / réactiver */
  suspendAccount(id: number): void {
    this.adminService.suspend(id).subscribe(() => {
      const acc = this.accounts.find(a => a.id === id);
      if (acc) acc.isSuspended = true;
    });
  }

  unsuspendAccount(id: number): void {
    this.adminService.unsuspendAccount(id).subscribe(() => {
      const acc = this.accounts.find(a => a.id === id);
      if (acc) acc.isSuspended = false;
    });
  }

 

} // <<<– N’OUBLIE PAS CE `}` !!!
