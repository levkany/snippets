"""
    Copyright (C) 2021 Lev knyazev - All Rights Reserved
    You may use, distribute and modify this code under the
    terms of the MIT license, which unfortunately won't be
    written for another century.
    You should have received a copy of the MIT license with
    this file. If not, please write to: levkany.dev@gmail.com , or visit : https://opensource.org/licenses/MIT
"""

def get_bookmarks(self, file):
        '''
        this function extract pdf file outlines / bookmarks into a parent:child format for easier usage in any frontend technologies
        big thanks for the developers of PyPDF2, without them, this function wouldn't exists :)
        NOTE:: this function will only work after the pdf file has been "remerged" using the PdfMergerClass
        '''

        # TODO:: convert the bookmarks to Parent:Child format

        status = False
        bookmarks = []
        try:
            reader = PdfFileReader(open(file, 'rb'))
            bookmarks = reader.getOutlines()
        
            def recursive(bookmark_list, flag=False):
                for index, bookmark in enumerate(bookmark_list):
                    if index+1 < len(bookmark_list) and isinstance(bookmark_list[index+1], list):
                        # add child array to parent
                        bookmark_list[index] = dict(bookmark_list[index])
                        bookmark_list[index]['/Children'] = bookmark_list[index+1]
                        del bookmark_list[index+1] # remove the "childs" from the list after adding them to the parent

                        # run recursive
                        recursive(bookmark_list[index]['/Children'], flag=True)
                        pass
            
            # run recursive
            recursive(bookmarks)

        except Exception as e:
            print('[ERROR] - failed to fetch pdf bookmarks')
            print(e)
        return bookmarks or status
