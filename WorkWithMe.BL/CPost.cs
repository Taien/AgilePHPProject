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
        public Guid? TargetGroupId { get; set; }
        public Guid? ReplyPostId { get; set; }
        public string Title { get; set; }
        public string Content { get; set; }
        public string OwnerFullName { get; set; }
        public bool IsSticky { get; set; }
        public bool IsDeleted { get; set; }
        public DateTime TimeStamp { get; set; }
        public DateTime? EventTimeStamp { get; set; }

        public CPost() { } //hello

        public CPost(Guid ownerUserId, Guid? targetGroupId, Guid? replyPostId, string title, string content, bool isSticky, bool isDeleted, DateTime timestamp, DateTime? eventTimestamp)
        {
            OwnerUserId = ownerUserId;
            TargetGroupId = targetGroupId == Guid.Empty ? null : targetGroupId;
            ReplyPostId = replyPostId == Guid.Empty ? null : replyPostId;
            Title = title;
            Content = content;
            IsSticky = isSticky;
            IsDeleted = isDeleted;
            TimeStamp = timestamp;
            EventTimeStamp = eventTimestamp;
        }

        public CPost(Guid id, Guid ownerUserId, Guid? targetGroupId, Guid? replyPostId, string title, string content, string ownerFullName, bool isSticky, bool isDeleted, DateTime timestamp, DateTime? eventTimestamp)
        {
            Id = id;
            OwnerUserId = ownerUserId;
            TargetGroupId = targetGroupId == Guid.Empty ? null : targetGroupId;
            ReplyPostId = replyPostId == Guid.Empty ? null : replyPostId;
            Title = title;
            Content = content;
            OwnerFullName = ownerFullName;
            IsSticky = isSticky;
            IsDeleted = isDeleted;
            TimeStamp = timestamp;
            EventTimeStamp = eventTimestamp; 
        }

        public CPost(Guid id)
        {
            Id = id; 
        }
        public int Create()
        {
            try
            {
                WorkWithMeDataContext oDC = new WorkWithMeDataContext();
                return oDC.spCreatePost(OwnerUserId, TargetGroupId, ReplyPostId, Title, Content, IsSticky, EventTimeStamp);
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
            post.ReplyPostId = ReplyPostId;
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

    public class CPostList : List<CPost>
    {
        public void LoadPostsForUser(Guid userId)
        {
            Clear();
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                List<spGetPostsForUserResult> list = oDC.spGetPostsForUser(userId).ToList();
                foreach (spGetPostsForUserResult item in list)
                {
                    CPost post = new CPost(item.Id, item.OwnerUserId, item.TargetGroupId, item.ReplyPostId, item.Title, item.Content, item.OwnerFullName, item.IsSticky, item.IsDeleted, item.TimeStamp, item.EventTimeStamp);
                    Add(post);
                }
            }
        }

        public void LoadOffsetPostsForUser(Guid userId, int offset)
        {
            Clear();
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                List<spGetOffsetPostsForUserResult> list = oDC.spGetOffsetPostsForUser(userId,offset).ToList();
                foreach (spGetOffsetPostsForUserResult item in list)
                {
                    CPost post = new CPost(item.Id, item.OwnerUserId, item.TargetGroupId, item.ReplyPostId, item.Title, item.Content, item.OwnerFullName, item.IsSticky, item.IsDeleted, item.TimeStamp, item.EventTimeStamp);
                    Add(post);
                }
            }
        }

        public void LoadRepliesForPost(Guid postId)
        {
            Clear();
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                List<spGetRepliesForPostResult> list = oDC.spGetRepliesForPost(postId).ToList();
                foreach (spGetRepliesForPostResult item in list)
                {
                    CPost post = new CPost(item.Id, item.OwnerUserId, item.TargetGroupId, item.ReplyPostId, item.Title, item.Content, item.OwnerFullName, item.IsSticky, item.IsDeleted, item.TimeStamp, item.EventTimeStamp);
                    Add(post);
                }
            }
        }
    }

}
