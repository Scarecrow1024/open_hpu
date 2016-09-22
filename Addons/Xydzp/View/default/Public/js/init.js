 $(function() {
            // wx.config({
            //     debug: true, // ��������ģʽ,���õ�����api�ķ���ֵ���ڿͻ���alert��������Ҫ�鿴����Ĳ�����������pc�˴򿪣�������Ϣ��ͨ��log���������pc��ʱ�Ż��ӡ��
            //     appId: 'wx8b7419eeb33a6e55', // ������ںŵ�Ψһ��ʶ
            //     timestamp: , // �������ǩ����ʱ���
            //     nonceStr: '', // �������ǩ���������
            //     signature: '',// ���ǩ��������¼1
            //     jsApiList: [] // �����Ҫʹ�õ�JS�ӿ��б�����JS�ӿ��б����¼2
            // });
     
     
				$("#wheelcanvas").rotate({
                                        duration: 1000,
                                        angle: 0,
                                        animateTo: -90,
                                        easing: $.easing.easeOutSine
										});	
                var jsonData = {};
                var isStart = false;
                
                $(document).attr("title","��������,һ�󲨺������...");
                function lottery() {
                    var start = function() {
                        if (isStart) {
                            return false
                        }
                        isStart = true;
                        $.ajax({
                            type: 'POST',
                            url: posturl,
                            dataType: 'json',
                            cache: false,
                            error: function() {
								alert('��ѽ��û���а�����˵������Ի�ø���齱�����Լ��н����ʣ�');  
								$(".weixin-tip").css("display","block");
								isStart = false;
                                return false
                            },
                            success: function(json) {
                                var type=json.type;
								switch(type){
									case '0':
										alert("���Ѿ�û�г齱������,��˵������Ի�ø���齱�����Լ��н����ʣ�");
										$(".weixin-tip").css("display","block");
										isStart = false;
										break;
									case '1':	
									case '2':
										var a = parseInt(json.angle);
										var p = json.praisename;
										
										if (p != "") {
											$("#count").html(json.num);
											document.getElementById("playyy").play();
											$("#wheelcanvas").rotate({
												duration: 10000,
												angle: -90,
												animateTo: 3600+a,
												easing: $.easing.easeOutSine,
												callback: function() {
												    if(json.isdf!=1){
    													$("#result").show();
    													$("#prizetype").html(p);
    													$(document).attr("title","��������,�ҳ鵽"+p+"��,��˵������Ի�ø���齱�����Լ��н����ʣ�...");
                                                        $(".weixin-tip").css("display","block");
												    }else{
												        //alert(p);
												    }
													isStart = false;
												}
											})
										}
										break;
									case '3':
										alert("���û�п�ʼŶ!");
										isStart = false;
										break;
									case '4':
										alert("��Ѿ���������");
										isStart = false;
										break;
								}
                                
                            }
                        });
                    };
                    $("#startbtn").bind("click", start)
                }
                var colors = ["#ff6501", "#f6f09f", "#FFFFFF"];     
				var colors1 = ["#f6f09f", "#ff6501"];     				
                var cwidth = 227;
                var startAngle = 0;
                var arc = Math.PI / jplist.length*2;
                var spinTimeout = null;
                var spinArcStart = 10;
                var spinTime = 0;
                var spinTimeTotal = 0;
                var ctx;
                function draw() {
                    drawRouletteWheel()
                }
                function drawRouletteWheel() {
                    var canvas = document.getElementById("wheelcanvas");
                    if (canvas.getContext) {
                        var outsideRadius = 97;
                        var textRadius = 80;
                        var insideRadius = 20;
                        ctx = canvas.getContext("2d");
						//�����µ�ͼƬ����
						var img = new Image();						
						//ָ��ͼƬ��URL						
						img.src = zpimg;					
						//���������ͼƬ��Ϻ��ٻ���ͼƬ						
						img.onload = function(){						
							ctx.clearRect(0, 0, 227, 227);											
							ctx.drawImage(img, 0, 0);
							ctx.strokeStyle = "#e9e8e5";
							ctx.lineWidth = 1;
							ctx.font = 'bold 14px ����';
							for (var i = 0; i < jplist.length; i++) {
								var angle = startAngle + i * arc;
								if (i % 2 == 1) {
									gradient = ctx.createRadialGradient(cwidth / 2, cwidth / 2, 0, cwidth / 2, cwidth / 2, cwidth / 2);
									gradient.addColorStop(0, '#fff');
									gradient.addColorStop(0.7, '#f6f09f');
									ctx.fillStyle = gradient
								} else {
									ctx.fillStyle = colors[(i % 2)];
								}
								ctx.beginPath();
								//����Ƭ��								
								ctx.arc(cwidth / 2, cwidth / 2, outsideRadius, angle, angle + arc, false);
								ctx.arc(cwidth / 2, cwidth / 2, insideRadius, angle + arc, angle, true);
								ctx.shadowBlur = 5;
								ctx.shadowColor = "#666";
								ctx.stroke();
								ctx.fill();
								ctx.save();
								ctx.shadowOffsetX = -1;
								ctx.shadowOffsetY = -1;
								ctx.shadowBlur = 0;
								ctx.shadowColor = "rgb(220,220,220)";
								ctx.fillStyle = "black";
								ctx.translate(cwidth / 2 + Math.cos(angle + arc / 2) * textRadius, cwidth / 2 + Math.sin(angle + arc / 2) * textRadius);
								ctx.rotate(angle + arc / 2 + Math.PI / 2);
								var text = jplist[i].title;
								//��������
								drawText(text,ctx,colors1[(i % 2)]);
								//ctx.fillText(text, -ctx.measureText(text).width / 2, 0);
								ctx.restore()
							}
						};
                    }
                } 
				
				function drawText(text,ctx,c){	
					ctx.fillStyle = c;
					for(var i=0;i<text.length;i++){						
						ctx.fillText(text[i], -ctx.measureText(text[i]).width / 2, i*13);
					}
				}
				
                draw();
                lottery();		
            });