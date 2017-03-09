using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL;

namespace WorkWithMe.BL
{
   public class CPost
    {
        public Guid Id { get; set; }
        public Guid OwnerUserId { get; set; }
        public Guid TargetGroupId { get; set; }
        public string Title { get; set; }
        public string Content { get; set; }
        public bool IsSticky { get; set; }
        public bool IsDeleted { get; set; }
        public DateTime TimeStamp { get; set; }
        public DateTime EventTimeStamp { get; set; }

        public CPost() { }

        public CPost(Guid id, Guid owneruserid, Guid targetgroupid, string title, string content, bool issticky, bool isdeleted, DateTime timestamp, DateTime eventtimestamp)
        {
            Id = id;
            OwnerUserId = owneruserid;
            TargetGroupId = targetgroupid;
            Title = title;
            Content = content;
            IsSticky = issticky;
            IsDeleted = isdeleted;
            TimeStamp = timestamp;
            EventTimeStamp = eventtimestamp; 
        }

        public void Insert(CPost post)
        {
            try
            {
                WorkWithMeDataContext oDC = new WorkWithMeDataContext();
                tblPost p = new tblPost();
                p.Id = Id;
                p.OwnerUserId = OwnerUserId;
                p.TargetGroupId = TargetGroupId;
                p.Title = Title;
                p.Content = Content;
                p.IsSticky = IsSticky;
                p.IsDeleted = IsDeleted;
                p.TimeStamp = TimeStamp;
                p.EventTimeStamp = EventTimeStamp; 
               

                oDC.tblPosts.InsertOnSubmit(p);
                oDC.SubmitChanges();
            }
            catch(Exception ex)
            {
                throw ex; 
            }
          

        }

        public void Update()
        {
            WorkWithMeDataContext oDC = new WorkWithMeDataContext();
            tblPost post = (from p in oDC.tblPosts where p.Id == Id select p).FirstOrDefault();

            post.Id = Id;
            post.OwnerUserId = OwnerUserId;
            post.TargetGroupId = TargetGroupId;
            post.Title = Title;
            post.Content = Content;
            post.IsSticky = IsSticky;
            post.IsDeleted = IsDeleted;
            post.TimeStamp = TimeStamp;
            post.EventTimeStamp = EventTimeStamp;

            oDC.SubmitChanges();

        }
        
        public void Delete()
        {
            WorkWithMeDataContext oDC = new WorkWithMeDataContext();

            tblPost post = (from p in oDC.tblPosts where p.Id == Id select p).FirstOrDefault();

            oDC.tblPosts.DeleteOnSubmit(post);
            oDC.SubmitChanges();
        }
    }
}
