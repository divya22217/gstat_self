

	function comp_note(filing_no,case_type,url){
		//var loader = "<center><img src='../loader/loader.gif'></img></center>";
		   $.ajax({
				type: "POST",
				url: url,
				data: {action:'get_form',filing_no:filing_no,case_type:case_type,url:url},
				beforeSend: function() {
					$("#comp_note").modal('show');
					$("#comp_note_body").html('loading....');
				},
				success: function (data) {
				   $("#comp_note_body").html(data);
				   //alert("success");
				},
				error: function (textStatus, errorThrown) {
					$("#comp_note_body").html('');
				   alert("error");
				}

			});	
	}
	$(document).ready(function(e){
		// Submit form data via Ajax
		$(document).on('click','#submit_computation_note_btn', function(e){
			e.preventDefault();
			var url = $("#url").val();
			 var filing_no = $("#filing_no").val();
			 var case_type = $("#case_type").val();
			 var impugned_order_dt = $("#impugned_order_dt").val();
			 var limitation_days = $("input[name='limitation_days']:checked").val();
			 var limitation_computed_dt = $("#limitation_computed_dt").val();
			 var limitation_expires_dt = $("#limitation_expires_dt").val();
			 var delay_remarks = $("#delay_remarks").val();
			 var presentation_dt = $("#presentation_dt").val();
			 var date_of_scrutiny = $("#date_of_scrutiny").val();
			 var intimation_defects_dt = $("#intimation_defects_dt").val();
			 var date_of_return = $("#date_of_return").val();
			 var date_of_representation = $("#date_of_representation").val();
			 var delay_represent_remarks = $("#delay_represent_remarks").val();
			 var caveat_filed = $("input[name='caveat_filed']:checked").val();
			 var ias = [];
            $.each($("input[name='all_ia']:checked"), function(){
                ias.push($(this).val());
            });
			swal({
            title: "Are you sure ??",
            text: "do you want to submit note!", 
            icon: "warning",
            buttons: true,
            dangerMode: false,
			})
			.then((willDelete) => { 
				 if (willDelete) {	
					$.ajax({
						type: "POST",
						url: url,
						data: {action:'save_note',filing_no:filing_no,case_type:case_type,impugned_order_dt:impugned_order_dt,limitation_days:limitation_days,
								limitation_computed_dt:limitation_computed_dt,limitation_expires_dt:limitation_expires_dt,delay_remarks:delay_remarks,presentation_dt:presentation_dt,
								date_of_scrutiny:date_of_scrutiny,intimation_defects_dt:intimation_defects_dt,date_of_return:date_of_return,date_of_representation:date_of_representation,
								delay_represent_remarks:delay_represent_remarks,caveat_filed:caveat_filed,ias:ias},
						/* contentType: false,
						cache: false,
						processData:false, */
						dataType: 'json',
						beforeSend:function(){ 
							$('#submit_computation_note_btn').attr("disabled","disabled");
							$('#submit_computation_note').css("opacity",".5");
						},
						success: function (response) {
							$('#submit_computation_note').css("opacity","");
							$("#submit_computation_note_btn").removeAttr("disabled");
							if(response.status == 0){
								swal('',response.message,'warning');
							}
							else{
								swal('',response.message,'success');
								setTimeout(function(){ location.reload(true); }, 3000);
							}
						},
						error: function (textStatus, errorThrown) {
						  console.log(textStatus);
						   alert(errorThrown);
						}

					}); 
					return false;
				 }else{
				 }
			});			
		}); 
	});
	
	$(document).ready(function(e){
		// Submit form data via Ajax
		$(document).on('click','#remark_btn', function(e){
			e.preventDefault();
			var url = $("#url").val();
			 var filing_no = $("#filing_no").val();
			 var case_type = $("#case_type").val();
			 var remark = $("#remark").val();
			 var is_approved = $("input[name='is_approved']:checked").val();
			 if(is_approved === '1'){
				var listing_date = $("#listing_date").val();
				var court_no = $("#court_no").val();
			 }else{
				var listing_date = '';
				var court_no = '';
			 }
			swal({
            title: "Are you sure ??",
            text: "do you want to submit remark!", 
            icon: "warning",
            buttons: true,
            dangerMode: false,
			})
			.then((willDelete) => { 
				 if (willDelete) {	
					$.ajax({
						type: "POST",
						url: url,
						data: {action:'save_remark',filing_no:filing_no,case_type:case_type,remark:remark,is_approved:is_approved,listing_date:listing_date,court_no:court_no},
						/* contentType: false,
						cache: false,
						processData:false, */
						dataType: 'json',
						beforeSend:function(){ 
							$('#remark_btn').attr("disabled","disabled");
							$('#remark_form').css("opacity",".5");
						},
						success: function (response) {
							$('#remark_form').css("opacity","");
							$("#remark_btn").removeAttr("disabled");
							if(response.status == 0){
								swal('',response.message,'warning');
							}
							else{
								swal('',response.message,'success');
								setTimeout(function(){ location.reload(true); }, 3000);
							}
						},
						error: function (textStatus, errorThrown) {
						  console.log(textStatus);
						   alert(errorThrown);
						}

					}); 
					return false;
				 }else{
				 }
			});			
		}); 
	});
	
	function show_note(filing_no,case_type,url){
		$.ajax({
				type: "POST",
				url: url,
				data: {action:'show_note',filing_no:filing_no,case_type:case_type,url:url},
				beforeSend: function() {
					$("#comp_note").modal('show');
					$("#comp_note_body").html('loading....');
				},
				success: function (data) {
				   $("#comp_note_body").html(data);
				   //alert("success");
				},
				error: function (textStatus, errorThrown) {
					$("#comp_note_body").html('');
				   alert("error");
				}

			});
	}
	
	function add_remark(filing_no,case_type,url){
		//var loader = "<center><img src='../loader/loader.gif'></img></center>";
		   $.ajax({
				type: "POST",
				url: url,
				data: {action:'get_remark_form',filing_no:filing_no,case_type:case_type,url:url},
				beforeSend: function() {
					$("#add_remark").modal('show');
					$("#add_remark_body").html('loading....');
				},
				success: function (data) {
					//alert(data);
				   $("#add_remark_body").html(data);
				   //alert("success");
				},
				error: function (textStatus, errorThrown) {
					$("#add_remark_body").html('');
				   alert("error");
				}

			});	
	}
	
	function generate_case_no(filing_no,case_type,url){
		swal({
		title: "Are you sure ??",
		text: "If yes than an automatic case number will be generated", 
		icon: "warning",
		buttons: true,
		dangerMode: false,
		})
		.then((willDelete) => { 
			 if (willDelete) {	
				$.ajax({
					type: "POST",
					url: url,
					data: {action:'generate_case_no',filing_no:filing_no,case_type:case_type},
					/* contentType: false,
					cache: false,
					processData:false, */
					dataType: 'json',
					beforeSend:function(){ 
						/* $('#remark_btn').attr("disabled","disabled");
						$('#remark_form').css("opacity",".5"); */
					},
					success: function (response) {
						if(response.status == 0){
							swal('',response.message,'warning');
						}
						else{
							swal('',response.message,'success');
							setTimeout(function(){ location.reload(true); }, 3000);
						}
					},
					error: function (textStatus, errorThrown) {
					  console.log(textStatus);
					   alert(errorThrown);
					}

				}); 
				return false;
			 }else{
			 }
		});
	}
	
	function view_docs(filing_no,case_type,url,pdf_rul){
		$.ajax({
				type: "POST",
				url: url,
				data: {action:'doc_list',filing_no:filing_no,case_type:case_type,url:url,pdf_rul:pdf_rul},
				beforeSend: function() {
					$("#view_doc").modal('show');
					$("#view_doc_body").html('loading....');
				},
				success: function (data) {
					//alert(data);
				   $("#view_doc_body").html(data);
				   //alert("success");
				},
				error: function (textStatus, errorThrown) {
					$("#view_doc_body").html('');
				   alert("error");
				}

			});
	}
	