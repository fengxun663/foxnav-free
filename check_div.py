#!/usr/bin/env python3
# -*- coding: utf-8 -*-

def check_div_tags(filename):
    """检查HTML文件中div标签的开启和关闭情况"""
    try:
        with open(filename, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # 计算div开始标签的数量
        div_open_pattern = r'<div(?:\s[^>]*)?>'
        div_opening = len(re.findall(div_open_pattern, content))
        
        # 计算div结束标签的数量  
        div_closing = content.count('</div>')
        
        print(f"检查文件: {filename}")
        print(f"<div 标签数量: {div_opening}")
        print(f"</div> 标签数量: {div_closing}")
        
        if div_opening == div_closing:
            print("✅ div标签完美匹配！")
            return True
        else:
            difference = div_opening - div_closing
            print(f"❌ div标签不匹配！差 {abs(difference)} 个")
            if difference > 0:
                print("   缺少结束标签 </div>")
            else:
                print("   缺少开启标签 <div>")
            return False
            
    except Exception as e:
        print(f"错误: {e}")
        return False

# 检查div标签
import re
check_div_tags('index.html')
