#include<stdio.h>//标准输入输出头文件
#include<string.h>//strcmp函数所需头文件
#include<stdlib.h>//FILE所需头文件

#define PATH "information.txt"//宏定义	学生信息存储目录

char information[1024][20];//程序运行开始从txt文档读取出的学生信息将会存储在这个数组，后面我们增删改查操作的都是这个二维数组

void delay(unsigned int z);
void Connection_Library();//连接数据库
void Menu();//菜单
void Preservation();//存档

FILE *fp;//读取txt或写入txt的指针
char chaxun[1][20];//查询时所需数组
char shanchu[1];//删除时所需数组
int i,j;//循环变量
int num=0;//录入学生信息时才会改变默认为0，也就相当于“录入”的学生人数
int Number_of_Student;//这是一个全局变量记录有多少个学生
int NUM;//读取时读到的字符串数后将转换为学生人数，该变量会多读取一个'\0'字符所以计算学生人数公式是Number_of_Student = (NUM-1)/8
int k;//key选择变量


	//（1）\t是对齐相当于tab
	//（2）\n是换行相当于enter
	//（3）exit(0)是结束整个程序的语句
	//（4）system("cls")是清屏语句
	

void main()
{
	printf("连接数据库中，请稍后···\n");
	delay(1553600);
	Connection_Library();//连接数据库
	delay(1553600);
	system("cls");//清屏
	Menu();//调用菜单

	while(1)
	{
		printf("\n请输入选择功能的序号:");
		scanf("%d",&k);
		switch(k)
		{
			case 0://退出程序
					exit(0);
			case 1://录入
			{
				printf("请输入所要录入的学生人数:");
				scanf("%d",&num);
				printf("学号\t姓名\t语文\t数学\t英语\t物理\t化学\t生物\t\n");
				for(i=(Number_of_Student*8);i<((num*8)+(Number_of_Student*8));i++)
				{
					scanf("%s",&information[i]);
				}
				Number_of_Student = ((num*8)+(NUM-1))/8;
				Preservation();

			}
			break;
			case 2://查询
			{
				//将整个学生信息数组也就是information全部搜索一遍此处是依据学号进行查询，怎么样避免搜索时把学生成绩当成学号来处理呢
				//也就是看检索到的此时的i是不是在学号的排列次序上
				//我们来分析一下数组的内部结构
				//（0）学号（1）姓名（2）语文（3）数学（4）英语（5）物理（6）化学（7）生物
				//前面是第一个同学的信息，然后来到第二个同学的信息
				//（8）学号（9）姓名（10）语文（11）数学（12）英语（13）物理（14）化学（15）生物
			    //以此类推就会发现，学生学号的位置数取余与8都是0——而这也就是我们区别成绩与学号的关键了
				//至于姓名大概也就和学号的查询方法差不多，姓名位与8取余都是1
				printf("请输入所查询学生学号或姓名:");
				scanf("%s",&chaxun[0]);
					for(i=0;i<1024;i++)	
					{
						if((strcmp(chaxun[0],information[i])==0)&&(i%8)==0)
						{
							printf("学号\t姓名\t语文\t数学\t英语\t物理\t化学\t生物\t\n");
							printf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t\n",information[i],information[i+1],information[i+2],information[i+3],information[i+4],information[i+5],information[i+6],information[i+7]);
							break;
						}
									
					}
					if(i<1024)
						break;

					for(i=0;i<1024;i++)
					{
						if(strcmp(chaxun[0],information[i])==0&&((i%8)==1))
						{
							printf("学号\t姓名\t语文\t数学\t英语\t物理\t化学\t生物\t\n");
							printf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t\n",information[i-1],information[i],information[i+1],information[i+2],information[i+3],information[i+4],information[i+5],information[i+6],information[i+7]);
							break;
						}
					}
					if(i<1024)				
						break;
					if(i==1024)
					{				
						printf("查询无果!!!\n");
						delay(3000000);
						system("cls");
						Menu();
						break;
					}
							
			}
			break;
			case 3://修改
			{
				printf("\n");
				printf("------成绩修改界面------\n");
				printf("请输入要修改的成绩同学的姓名或学号:");
				scanf("%s",&chaxun[0]);
				for(i=0;i<1024;i++)//按学号查询并且修改
				{
					if((strcmp(chaxun[0],information[i])==0)&&(i%8)==0)
					{
						printf("当前该生成绩:\n");
						printf("学号\t姓名\t语文\t数学\t英语\t物理\t化学\t生物\t\n");
						printf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t\n",information[i],information[i+1],information[i+2],information[i+3],information[i+4],information[j+5],information[i+6],information[i+7]);
						printf("请输入该生成绩:\n");
						printf("学号\t姓名\t语文\t数学\t英语\t物理\t化学\t生物\t\n");
						for(i,j=0;j<8;j++)
						{
							scanf("%s",&information[i+j]);
						}
						Preservation();
						break;
					}				
				}
				if(i<1024&&(i!=0))
				{				
					break;
				}
				
				for(i=0;i<1024;i++)//按姓名查询并且修改
				{
					if(strcmp(chaxun[0],information[i])==0&&((i%8)==1))
					{
						printf("当前该生成绩:\n");
						printf("学号\t姓名\t语文\t数学\t英语\t物理\t化学\t生物\t\n");
						printf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t\n",information[i-1],information[i],information[i+1],information[i+2],information[i+3],information[i+4],information[i+5],information[i+6],information[i+7]);
						printf("请输入该生成绩:\n");
						printf("学号\t姓名\t语文\t数学\t英语\t物理\t化学\t生物\t\n");
						for(i,j=0;j<8;j++)
						{
							scanf("%s",&information[i+j-1]);
						}
					//	for(i;i<9;i++)
					//	{
					////		scanf("%s",&information[i-1]);//修改的意思就是重新给数组原来的学生信息赋值，将原信息覆盖
					//	}
						Preservation();
						break;
					}
				}
				if(i<1024&&(i!=0))
					break;
				if(i==1024)
				{				
					printf("查询无果!!!\n");
					delay(3000000);
					system("cls");
					Menu();
					break;
				}
				
			}
			case 4://打印ALL
			{
				printf("学号\t姓名\t语文\t数学\t英语\t物理\t化学\t生物\t\n");
				for(i=1;i<=Number_of_Student*8;i++)
				{
					printf("%s\t",information[i-1]);
					if(i%8==0)
					printf("\n");
				}
			}
			break;
			case 5://删除指定同学成绩信息
			{
				printf("------成绩删除界面------\n");
				printf("请输入所要删除学生信息的学号或姓名:");
				scanf("%s",&chaxun[0]);
				for(i=0;i<1024;i++)//按学号查询并且删除
				{
					if((strcmp(chaxun[0],information[i])==0)&&(i%8)==0)
					{
						printf("当前该生成绩:\n");
						printf("学号\t姓名\t语文\t数学\t英语\t物理\t化学\t生物\t\n");
						printf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t\n",information[i],information[i+1],information[i+2],information[i+3],information[i+4],information[j+5],information[i+6],information[i+7]);
						//以下是删除操作
						printf("提示:确定要删除该生成绩？(Y or N)\n");
						scanf("%s",&shanchu[0]);
						if(shanchu[0]=='Y')
						{
							for(i;i<Number_of_Student*8;i++)
							{
								for(j=0;j<20;j++)//内循环20次把第二维数组都给移位
								{
										information[i][j]=information[i+8][j];//此处的删除操作是指将被删除的学生信息覆盖，也就避免了输出空行
										//这里有information[i+8][j]是因为此时的i是被删除学生所对应的学号，所以数组中的值是把i+8复制给i
								}
							
							}
							Number_of_Student = Number_of_Student-1;
							Preservation();
							printf("删除成功!!!\n");
							delay(3000000);
							break;
						}
						else
							break;

					}				
				}
				if(i<1024)
				{				
					break;
				}
				
				for(i=0;i<1024;i++)//按姓名查询并且删除
				{
					if(strcmp(chaxun[0],information[i])==0&&((i%8)==1))
					{
						printf("当前该生成绩:\n");
						printf("学号\t姓名\t语文\t数学\t英语\t物理\t化学\t生物\t理综合\t总分\t\n");
						printf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t\n",information[i-1],information[i],information[i+1],information[i+2],information[i+3],information[i+4],information[i+5],information[i+6],information[i+7]);
						//以下是删除操作-----上面的i被保留以便于后边进行删除操作
						printf("提示:确定要删除该生成绩？(Y or N)\n");
						scanf("%s",&shanchu[0]);
						if(shanchu[0]=='Y')
						{
							for(i;i<Number_of_Student*8;i++)
							{
								for(j=0;j<20;j++)
								{
										information[i-1][j]=information[i+7][j];//此处的删除操作是指将被删除的学生信息覆盖，也就避免了输出空行
										//这里有information[i+7][j]是因为此时的i是被删除学生所对应的姓名，所以数组中的值是把i+7复制给i-1
								}
							
							}
							Number_of_Student = Number_of_Student-1;
							Preservation();
							printf("删除成功!!!\n");
							delay(3000000);
							break;
						}
						else
							break;


						
					}
				}
				if(i<1024)//看此上边是否已经完成删除若删除则i<1024则此时跳出case				
					break;
				if(i==1024)//当i=1024时还没有查找到该学生则进入以下语句输出查询未果
				{				
					printf("查询无果!!!\n");
					delay(3000000);
					system("cls");
					Menu();
					break;
				}

			}

			case 6://清屏
			{
				system("cls");
				//----由于已经清屏为了简单直接清除全部所以需重新输出功能选择区----//
				Menu();
			}
			break;
		}



	}
								

}


void delay(unsigned int z)
{
    unsigned int x,y;
    for(x=z;x>0;x--)
    for(y=110;y>0;y--);
}

void Connection_Library()//检查是否打开文件函数并将从文本库读取出的信息存入数组information[]
{


	fp = fopen("information.txt","r");
	if (fp == NULL)
		{
			printf("连接失败\n");
		}
	else
		{
		NUM=0;
		while(!feof(fp))
				{
					fscanf(fp,"%s",information[NUM]);
					NUM++;
				}
		printf("连接成功\n");
		Number_of_Student = (NUM-1)/8;
		}
}

void Menu()//---功能选择区
{
	printf("-------菜单-------\n\n");
	printf("(1) 录入成绩\n");
	printf("(2) 查询成绩\n");
	printf("(3) 修改信息\n");
	printf("(4) 打印全部成绩\n");
	printf("(5) 删除成绩\n");
	printf("(6) 清除屏幕\n");
	printf("(0) 退出程序\n");
	printf("\n");
	printf("必须在功能界面输入(0)以退出系统 否则数据将无法保存\n");
	printf("\n");
	printf("数据库中现在共有-%d-名同学信息\n",Number_of_Student);
}

void Preservation()
{
	printf("正在存档请稍后···\n");
	//------存档学生信息------//
	fp=fopen("information.txt","w");
	for(i=0;i<Number_of_Student*8;i++)
		fprintf(fp,"%s ",information[i]);//将数组a的内容写入文件，以空格隔开
	printf("\n");
	delay(3000000);
	printf("存档完毕\n");
	delay(3000000);
}

//--------------Copy BaiYuQing Stdio--------------//
//-------------------2020-12-11-------------------//
