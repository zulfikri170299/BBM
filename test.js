
function rendisForm() {
    const twMap = {
        'TW I': ['Januari','Februari','Maret'], 'TW II': ['April','Mei','Juni'],
        'TW III': ['Juli','Agustus','September'], 'TW IV': ['Oktober','November','Desember'],
    };
    return {
        triwulan: '"DUMMY"',
        hari: {
            b1_op: "DUMMY", b1_st: "DUMMY", b1_pi: "DUMMY",
            b2_op: "DUMMY", b2_st: "DUMMY", b2_pi: "DUMMY",
            b3_op: "DUMMY", b3_st: "DUMMY", b3_pi: "DUMMY",
        },
        get namaBulan() { return twMap[this.triwulan] || ['Bulan 1','Bulan 2','Bulan 3']; },
        getHari(bulan, kategori) {
            const h = this.hari;
            const k = kategori;
            if (bulan === 1) return k === 'pimpinan' ? h.b1_pi : (k === 'staff' ? h.b1_st : h.b1_op);
            if (bulan === 2) return k === 'pimpinan' ? h.b2_pi : (k === 'staff' ? h.b2_st : h.b2_op);
            return k === 'pimpinan' ? h.b3_pi : (k === 'staff' ? h.b3_st : h.b3_op);
        },
        init() {
            // Watch removed in favor of manual apply button
            
            // Initial calc
            setTimeout(() => {
                this.recalculateAll();
            }, 100);
        },
        handleTableInput(event) {
            const target = event.target;
            const tr = target.closest('tr');
            if (!tr) return;

            if (target.classList.contains('input-uraian')) {
                tr.dataset.kategori = target.value.toLowerCase();
                this.updateRowTotals(tr);
                this.recalculateSatker(tr.dataset.satkerId);
            } else if (target.classList.contains('input-lph-1')) {
                const val = target.value;
                tr.querySelector('.input-lph-2').value = val;
                tr.querySelector('.input-lph-3').value = val;
                this.updateRowTotals(tr);
                this.recalculateSatker(tr.dataset.satkerId);
            } else if (target.classList.contains('input-lph-2') || target.classList.contains('input-lph-3')) {
                this.updateRowTotals(tr);
                this.recalculateSatker(tr.dataset.satkerId);
            }
        },
        updateRowTotals(tr) {
            const kategori = tr.dataset.kategori;
            const lph1 = parseFloat(tr.querySelector('.input-lph-1').value) || 0;
            const lph2 = parseFloat(tr.querySelector('.input-lph-2').value) || 0;
            const lph3 = parseFloat(tr.querySelector('.input-lph-3').value) || 0;
            
            const hari1 = this.getHari(1, kategori);
            const hari2 = this.getHari(2, kategori);
            const hari3 = this.getHari(3, kategori);

            if (tr.querySelector('.span-hari-1')) tr.querySelector('.span-hari-1').innerText = hari1;
            if (tr.querySelector('.span-hari-2')) tr.querySelector('.span-hari-2').innerText = hari2;
            if (tr.querySelector('.span-hari-3')) tr.querySelector('.span-hari-3').innerText = hari3;

            const b1Total = Math.round(lph1 * hari1);
            const b2Total = Math.round(lph2 * hari2);
            const b3Total = Math.round(lph3 * hari3);
            
            tr.querySelector('.input-b1-total').value = b1Total;
            tr.querySelector('.input-b2-total').value = b2Total;
            tr.querySelector('.input-b3-total').value = b3Total;
            
            if (tr.querySelector('.span-b1-total')) tr.querySelector('.span-b1-total').innerText = b1Total;
            if (tr.querySelector('.span-b2-total')) tr.querySelector('.span-b2-total').innerText = b2Total;
            if (tr.querySelector('.span-b3-total')) tr.querySelector('.span-b3-total').innerText = b3Total;
            
            if (tr.querySelector('.span-b1-total-dex')) tr.querySelector('.span-b1-total-dex').innerText = b1Total;
            if (tr.querySelector('.span-b2-total-dex')) tr.querySelector('.span-b2-total-dex').innerText = b2Total;
            if (tr.querySelector('.span-b3-total-dex')) tr.querySelector('.span-b3-total-dex').innerText = b3Total;
        },
        recalculateSatker(satkerId) {
            let p1=0, d1=0, p2=0, d2=0, p3=0, d3=0;
            const rows = document.querySelectorAll(`tr.kendaraan-row[data-satker-id="${satkerId}"]`);
            rows.forEach(tr => {
                const jenis = tr.dataset.jenis;
                const b1 = parseInt(tr.querySelector('.input-b1-total').value) || 0;
                const b2 = parseInt(tr.querySelector('.input-b2-total').value) || 0;
                const b3 = parseInt(tr.querySelector('.input-b3-total').value) || 0;
                
                if (jenis === 'pertamax') {
                    p1 += b1; p2 += b2; p3 += b3;
                } else {
                    d1 += b1; d2 += b2; d3 += b3;
                }
            });
            const satkerTr = document.querySelector(`tr.satker-total[data-satker-id="${satkerId}"]`);
            if (satkerTr) {
                satkerTr.querySelector('.st-p1').innerText = p1;
                satkerTr.querySelector('.st-d1').innerText = d1;
                satkerTr.querySelector('.st-p2').innerText = p2;
                satkerTr.querySelector('.st-d2').innerText = d2;
                satkerTr.querySelector('.st-p3').innerText = p3;
                satkerTr.querySelector('.st-d3').innerText = d3;
            }
        },
        recalculateAllSatkers() {
            const satkerIds = new Set();
            document.querySelectorAll('tr.kendaraan-row').forEach(tr => {
                if(tr.dataset.satkerId) satkerIds.add(tr.dataset.satkerId);
            });
            satkerIds.forEach(id => this.recalculateSatker(id));
        },
        recalculateAll() {
            document.querySelectorAll('tr.kendaraan-row').forEach(tr => {
                this.updateRowTotals(tr);
            });
            this.recalculateAllSatkers();
        }
    }
}
