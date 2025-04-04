import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PublicationTrajetComponent } from './publication-trajet.component';

describe('PublicationTrajetComponent', () => {
  let component: PublicationTrajetComponent;
  let fixture: ComponentFixture<PublicationTrajetComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [PublicationTrajetComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(PublicationTrajetComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
